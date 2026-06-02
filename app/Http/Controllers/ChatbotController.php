<?php

namespace App\Http\Controllers;

use App\Models\ChatbotHistory;
use App\Models\Lahan;
use App\Models\EnvironmentalMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
        $petani = Auth::user()->petani;
        $lahanList = $petani ? $petani->lahan : collect();

        $selectedLahanId = $request->get('lahan_id') ?? ($lahanList->first()->id ?? null);

        return view('chatbot', compact('lahanList', 'selectedLahanId'));
    }

    /**
     * Ambil daftar sesi history chat, dikelompokkan berdasarkan tanggal.
     * Dipanggil via AJAX dari sidebar.
     */
    public function history(Request $request)
    {
        $userId = Auth::id();

        // Ambil semua sesi unik milik user, sorted by latest
        $sessions = ChatbotHistory::where('user_id', $userId)
            ->select('session_id', 'lahan_id', 'question', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('session_id') // Ambil 1 record per sesi (yang pertama = pertanyaan pertama)
            ->values();

        $today = now()->startOfDay();
        $weekAgo = now()->subDays(7)->startOfDay();

        $grouped = [
            'today'    => [],
            'pastWeek' => [],
            'older'    => [],
        ];

        foreach ($sessions as $session) {
            $createdAt = $session->created_at;
            $title = Str::limit($session->question, 45);

            $entry = [
                'id'        => $session->session_id,
                'title'     => $title,
                'lahan_id'  => $session->lahan_id,
            ];

            if ($createdAt->gte($today)) {
                $grouped['today'][] = $entry;
            } elseif ($createdAt->gte($weekAgo)) {
                $grouped['pastWeek'][] = $entry;
            } else {
                $grouped['older'][] = $entry;
            }
        }

        return response()->json([
            'status' => 'success',
            'data'   => $grouped,
        ]);
    }

    /**
     * Ambil semua pesan dalam satu sesi chat berdasarkan session_id.
     */
    public function historyDetail(Request $request, $sessionId)
    {
        $records = ChatbotHistory::where('user_id', Auth::id())
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($records->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Sesi tidak ditemukan.'], 404);
        }

        // Ubah setiap record menjadi pasangan user + bot message
        $messages = [];
        foreach ($records as $record) {
            $messages[] = [
                'role' => 'user',
                'text' => $record->question,
                'time' => $record->created_at->format('H:i'),
            ];
            $messages[] = [
                'role' => 'bot',
                'text' => $record->answer,
                'time' => $record->created_at->format('H:i'),
            ];
        }

        return response()->json([
            'status' => 'success',
            'data'   => $messages,
        ]);
    }

    /**
     * Proses pertanyaan baru dan simpan ke database.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'lahan_id'   => 'required|exists:lahan,id',
            'question'   => 'required|string',
            'session_id' => 'nullable|string', // Boleh nullable, nanti di-generate kalau null
        ]);

        $lahan = Lahan::find($request->lahan_id);

        // Gunakan session_id dari request, atau buat baru jika belum ada
        $sessionId = $request->session_id ?: (string) Str::uuid();

        // 1. Ambil metrik IoT terbaru sebagai konteks
        $latestMetric = EnvironmentalMetric::whereHas('iotDevice', function ($q) use ($lahan) {
            $q->where('lahan_id', $lahan->id);
        })->latest('recorded_at')->first();

        // 2. Ambil riwayat percakapan dalam sesi ini untuk konteks multi-turn
        $conversationHistory = ChatbotHistory::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        // 3. Susun prompt dengan konteks data sensor + riwayat percakapan
        $contextPrompt = "Anda adalah sistem pakar AI pertanian pintar AGA Smart Farming.\n";
        $contextPrompt .= "Konteks Lahan Saat Ini:\n";
        $contextPrompt .= "- Nama Lahan: {$lahan->nama_lahan}\n";

        if ($latestMetric) {
            $contextPrompt .= "- Suhu Udara: {$latestMetric->temperature}°C\n";
            $contextPrompt .= "- Kelembapan Udara: {$latestMetric->humidity}%\n";
            $contextPrompt .= "- Kelembapan Tanah (pH sensor): {$latestMetric->ph}%\n";
            $contextPrompt .= "- Intensitas Cahaya: {$latestMetric->light_intensity} lux\n";
        } else {
            $contextPrompt .= "- Data sensor IoT belum tersedia.\n";
        }

        // Tambahkan riwayat percakapan jika ada
        if ($conversationHistory->isNotEmpty()) {
            $contextPrompt .= "\nRiwayat percakapan sebelumnya dalam sesi ini:\n";
            foreach ($conversationHistory as $chat) {
                $contextPrompt .= "Petani: {$chat->question}\n";
                $contextPrompt .= "AGA AI: {$chat->answer}\n";
            }
        }

        $contextPrompt .= "\nPertanyaan Petani Sekarang: " . $request->question;

        try {
            $endpoint = config('rag.endpoint') . '/recommend';

            $response = Http::timeout(config('rag.timeout', 30))
                ->withToken(config('rag.token'))
                ->asForm()
                ->post($endpoint, [
                    'prompt' => $contextPrompt,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $answer = $result['recommendationn'] ?? $result['recommendation'] ?? 'Maaf, model tidak memberikan jawaban.';
            } else {
                $answer = "Maaf, sistem AI sedang sibuk. (Status: " . $response->status() . ")";
            }
        } catch (\Exception $e) {
            $answer = "Terjadi kegagalan koneksi ke server AI: " . $e->getMessage();
        }

        // 4. Simpan ke database
        $chatRecord = ChatbotHistory::create([
            'user_id'    => Auth::id(),
            'lahan_id'   => $lahan->id,
            'session_id' => $sessionId,
            'question'   => $request->question,
            'answer'     => $answer,
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'answer'     => $chatRecord->answer,
                'session_id' => $chatRecord->session_id,
                'time'       => $chatRecord->created_at->format('H:i'),
            ],
        ]);
    }
}