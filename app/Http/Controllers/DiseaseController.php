<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiseaseController extends Controller
{
    /**
     * Tampilkan halaman klasifikasi penyakit
     */
    public function index()
    {
        return view('disease');
    }

    /**
     * =========================
     * ANALYZE IMAGE VIA LLM
     * Endpoint: POST /disease/analyze
     * =========================
     */
    public function analyze(Request $request)
    {
        // --- Validasi Input ---
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // max 5MB
        ]);

        $file = $request->file('image');

        // --- Encode gambar ke Base64 ---
        $imageData   = base64_encode(file_get_contents($file->getRealPath()));
        $mimeType    = $file->getMimeType(); // e.g. "image/jpeg"

        // --- Bangun prompt untuk LLM Vision ---
        $prompt = $this->buildDiseasePrompt();

        try {
            $ngrokUrl = config('rag.endpoint');

            $response = Http::timeout(60)
                ->withToken(config('rag.token'))
                ->asMultipart()
                ->post("{$ngrokUrl}/classify-disease", [
                    [
                        'name'     => 'prompt',
                        'contents' => $prompt,
                    ],
                    [
                        'name'     => 'image_base64',
                        'contents' => $imageData,
                    ],
                    [
                        'name'     => 'mime_type',
                        'contents' => $mimeType,
                    ],
                ]);

            if (! $response->successful()) {
                Log::error('LLM Vision Error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                throw new \Exception("HTTP {$response->status()}: {$response->body()}");
            }

            $data = $response->json();

            // --- Map semua field dari respons LLM ---
            $diseaseName = $data['disease_name']
                ?? $data['disease']
                ?? $data['result']
                ?? 'Tidak dapat diidentifikasi';

            $explanation = $data['explanation']
                ?? $data['description']
                ?? $data['recommendation']
                ?? '';

            $confidence = isset($data['confidence'])
                ? round($data['confidence'] * 100, 1) . '%'
                : null;

            $cause      = $data['cause']      ?? $data['reason']  ?? '';
            $treatment  = $data['treatment']  ?? $data['cure']    ?? '';
            $prevention = $data['prevention'] ?? $data['prevent'] ?? '';

            return response()->json([
                'status'       => 'success',
                'disease_name' => $diseaseName,
                'explanation'  => $explanation,
                'confidence'   => $confidence,
                'cause'        => $cause,
                'treatment'    => $treatment,
                'prevention'   => $prevention,
            ]);
        } catch (\Throwable $e) {
            Log::error('DiseaseController@analyze Exception', ['error' => $e->getMessage()]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghubungi server AI: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * =========================
     * PROMPT BUILDER
     * =========================
     */
    private function buildDiseasePrompt(): string
    {
        return <<<PROMPT
Anda adalah AI pakar penyakit tanaman pertanian.

TUGAS:
1. Analisis gambar daun/tanaman yang diberikan.
2. Identifikasi penyakit atau kondisi tanaman.
3. Berikan respons HANYA dalam format JSON berikut:

{
  "disease_name": "Nama penyakit dalam Bahasa Indonesia (dan nama ilmiahnya)",
  "confidence": 0.00,
  "explanation": "Penjelasan singkat gejala yang terlihat",
  "cause": "Penyebab utama penyakit ini",
  "treatment": "Langkah penanganan yang disarankan",
  "prevention": "Cara pencegahan"
}

ATURAN:
- Jika gambar bukan daun/tanaman, isi disease_name dengan "Bukan Tanaman" dan confidence 0.
- Jika tanaman sehat, isi disease_name dengan "Tanaman Sehat".
- confidence adalah nilai 0.0 sampai 1.0.
- HANYA balas dengan JSON, tanpa teks tambahan apapun.
PROMPT;
    }
}
