<?php

namespace App\Http\Controllers;

use App\Models\RagDocument;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RagDocumentController extends Controller
{
    // ------------------------------------------------------------------
    // Allowed MIME types mapped to extension
    // ------------------------------------------------------------------
    private const ALLOWED_MIMES = [
        'application/pdf'  => 'pdf',
        'text/plain'       => 'txt',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
    ];

    private const MAX_FILE_SIZE_MB = 10;

    // ==================================================================
    // WEB ROUTES
    // ==================================================================

    /**
     * Tampilan halaman manajemen RAG.
     */
    public function index()
    {
        $documents = RagDocument::with('uploadedBy')
            ->latest()
            ->paginate(15);

        return view('rag.index', compact('documents'));
    }

    /**
     * Handle upload dari form blade.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'rag_document' => [
                'required',
                'file',
                'max:' . (self::MAX_FILE_SIZE_MB * 1024),
                'mimes:pdf,txt,docx',
            ],
        ], [
            'rag_document.required' => 'Harap pilih file dokumen.',
            'rag_document.file'     => 'Upload harus berupa file.',
            'rag_document.max'      => 'Ukuran file maksimal ' . self::MAX_FILE_SIZE_MB . 'MB.',
            'rag_document.mimes'    => 'Format file harus PDF, TXT, atau DOCX.',
        ]);

        $doc = $this->storeDocument($request->file('rag_document'));
        try {
            Http::withToken(config('rag.token')) // Mengambil RAG_SERVICE_TOKEN
                ->timeout(config('rag.timeout')) // Mengambil RAG_TIMEOUT
                ->post(config('rag.endpoint') . '/api/process-document', [
                    'document_id' => $doc->id,
                    'file_path'   => $doc->file_path,
                    'collection'  => $doc->collection_name
                ]);
        } catch (\Exception $e) {
            // Log error jika Python service mati, tapi Laravel tetap aman
            Log::error("Gagal trigger Python RAG: " . $e->getMessage());
        }

        return redirect()->route('rag.index')
            ->with('success', "Dokumen \"{$doc->original_filename}\" berhasil diunggah dan sedang antri untuk diproses.");
    }

    /**
     * Hapus dokumen (soft delete).
     */
    public function destroy(RagDocument $ragDocument)
    {
        $ragDocument->delete();

        return redirect()->route('rag.index')
            ->with('success', "Dokumen \"{$ragDocument->original_filename}\" berhasil dihapus.");
    }

    // ==================================================================
    // API ROUTES  (dikonsumsi oleh tim LLM)
    // ==================================================================

    /**
     * GET /api/rag/documents
     * List semua dokumen dengan filter opsional.
     *
     * Query params:
     *   - status        : uploaded | processing | processed | failed
     *   - collection    : nama koleksi
     *   - per_page      : jumlah per halaman (default 20)
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = RagDocument::query();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('collection')) {
            $query->byCollection($request->collection);
        }

        $documents = $query->latest()->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $documents->items(),
            'meta'    => [
                'current_page' => $documents->currentPage(),
                'last_page'    => $documents->lastPage(),
                'per_page'     => $documents->perPage(),
                'total'        => $documents->total(),
            ],
        ]);
    }

    /**
     * POST /api/rag/documents
     * Upload dokumen baru via API.
     *
     * Body (multipart/form-data):
     *   - file            : file dokumen (pdf / txt / docx)
     *   - collection_name : (opsional) nama koleksi di vector DB
     */
    public function apiStore(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:' . (self::MAX_FILE_SIZE_MB * 1024),
                'mimes:pdf,txt,docx',
            ],
            'collection_name' => ['nullable', 'string', 'max:100'],
        ]);

        $doc = $this->storeDocument(
            $request->file('file'),
            $request->input('collection_name', 'default')
        );

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah.',
            'data'    => $doc,
        ], 201);
    }

    /**
     * GET /api/rag/documents/{id}
     * Detail satu dokumen.
     */
    public function apiShow(RagDocument $ragDocument): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $ragDocument,
        ]);
    }

    /**
     * GET /api/rag/documents/{id}/download
     * Generate temporary signed URL untuk download file.
     */
    public function apiDownload(RagDocument $ragDocument): JsonResponse
    {
        if (! Storage::exists($ragDocument->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan di storage.',
            ], 404);
        }

        // Untuk S3 / cloud storage: gunakan temporaryUrl()
        // Untuk local storage    : kembalikan path publik atau stream
        $url = Storage::temporaryUrl(
            $ragDocument->file_path,
            now()->addMinutes(30)
        );

        return response()->json([
            'success'    => true,
            'url'        => $url,
            'expires_at' => now()->addMinutes(30)->toIso8601String(),
        ]);
    }

    /**
     * PATCH /api/rag/documents/{id}/status
     * LLM team memperbarui status pemrosesan dokumen.
     *
     * Body (JSON):
     *   - status        : processing | processed | failed  (required)
     *   - chunk_count   : int  (wajib jika status = processed)
     *   - token_count   : int  (opsional)
     *   - error_message : string (wajib jika status = failed)
     */
    public function apiUpdateStatus(Request $request, RagDocument $ragDocument): JsonResponse
    {
        $request->validate([
            'status'        => ['required', 'in:processing,processed,failed'],
            'chunk_count'   => ['nullable', 'integer', 'min:0'],
            'token_count'   => ['nullable', 'integer', 'min:0'],
            'error_message' => ['nullable', 'string'],
        ]);

        match ($request->status) {
            'processing' => $ragDocument->markAsProcessing(),
            'processed'  => $ragDocument->markAsProcessed(
                $request->integer('chunk_count'),
                $request->integer('token_count')
            ),
            'failed'     => $ragDocument->markAsFailed(
                $request->input('error_message', 'Unknown error')
            ),
        };

        return response()->json([
            'success' => true,
            'message' => 'Status dokumen berhasil diperbarui.',
            'data'    => $ragDocument->fresh(),
        ]);
    }

    /**
     * DELETE /api/rag/documents/{id}
     * Soft-delete dokumen via API.
     */
    public function apiDestroy(RagDocument $ragDocument): JsonResponse
    {
        $ragDocument->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dihapus.',
        ]);
    }

    /**
     * GET /api/rag/documents/pending
     * Ambil semua dokumen dengan status uploaded (belum diproses LLM).
     * Endpoint polling untuk LLM worker.
     */
    public function apiPending(): JsonResponse
    {
        $documents = RagDocument::pending()
            ->oldest() // FIFO — proses yang paling lama diunggah duluan
            ->get();

        return response()->json([
            'success' => true,
            'count'   => $documents->count(),
            'data'    => $documents,
        ]);
    }

    // ==================================================================
    // PRIVATE HELPERS
    // ==================================================================

    /**
     * Simpan file ke storage dan buat record di DB.
     */
    private function storeDocument($file, string $collection = 'default'): RagDocument
    {
        $originalName = $file->getClientOriginalName();
        $extension    = strtolower($file->getClientOriginalExtension());
        $storedName   = Str::uuid() . '.' . $extension;
        $path         = $file->storeAs('rag_documents', $storedName, 'local');

        return RagDocument::create([
            'original_filename' => $originalName,
            'stored_filename'   => $storedName,
            'file_path'         => $path,
            'file_type'         => $extension,
            'file_size'         => $file->getSize(),
            'mime_type'         => $file->getMimeType(),
            'status'            => RagDocument::STATUS_UPLOADED,
            'collection_name'   => $collection,
            'uploaded_by'       => Auth::id(), // null when called via service-to-service API
        ]);
    }
}
