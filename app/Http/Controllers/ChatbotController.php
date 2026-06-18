<?php

namespace App\Http\Controllers;

use App\Models\ChatbotHistory;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * =========================
     * INDEX (ROLE-BASED LAHAN)
     * =========================
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {

            $lahanList = Lahan::with(['petani.user', 'komoditas'])
                ->orderBy('nama_lahan')
                ->get();
        } else {

            $petani = $user->petani;

            $lahanList = $petani
                ? $petani->lahan()->with(['komoditas'])->get()
                : collect();
        }

        $selectedLahanId = $request->get('lahan_id')
            ?? $lahanList->first()?->id;

        return view('chatbot', compact('lahanList', 'selectedLahanId'));
    }

    /**
     * =========================
     * HISTORY LIST
     * =========================
     */
    public function history()
    {
        $userId = Auth::id();

        $sessions = ChatbotHistory::where('user_id', $userId)
            ->select('session_id', 'lahan_id', 'question', 'created_at')
            ->orderByDesc('created_at')
            ->get()
            ->unique('session_id')
            ->values();

        $grouped = [
            'today' => [],
            'pastWeek' => [],
            'older' => [],
        ];

        $today = now()->startOfDay();
        $weekAgo = now()->subDays(7)->startOfDay();

        foreach ($sessions as $session) {

            $entry = [
                'id' => $session->session_id,
                'title' => Str::limit($session->question, 50),
                'lahan_id' => $session->lahan_id,
            ];

            if ($session->created_at->gte($today)) {
                $grouped['today'][] = $entry;
            } elseif ($session->created_at->gte($weekAgo)) {
                $grouped['pastWeek'][] = $entry;
            } else {
                $grouped['older'][] = $entry;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $grouped,
        ]);
    }

    /**
     * =========================
     * HISTORY DETAIL
     * =========================
     */
    public function historyDetail($sessionId)
    {
        $records = ChatbotHistory::where('user_id', Auth::id())
            ->where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        if ($records->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session tidak ditemukan'
            ], 404);
        }

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
            'data' => $messages,
        ]);
    }

    /**
     * =========================
     * ASK AI (MAIN FLOW)
     * =========================
     */
    public function ask(Request $request)
    {
        $request->validate([
            'lahan_id' => 'required|exists:lahan,id',
            'question' => 'required|string',
            'session_id' => 'nullable|string',
        ]);

        $lahan = Lahan::with(['komoditas', 'devices.latestReading'])
            ->find($request->lahan_id);

        if (!$lahan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lahan tidak ditemukan'
            ], 404);
        }

        $sessionId = $request->session_id ?: (string) Str::uuid();

        $device = $lahan->devices?->first();
        $sensor = $device?->latestReading;

        $weather = $this->getWeatherData($lahan->lokasi ?? '');

        $history = ChatbotHistory::where('session_id', $sessionId)
            ->where('user_id', Auth::id())
            ->orderBy('created_at')
            ->get();

        $prompt = $this->buildPrompt(
            $lahan,
            $sensor,
            $weather,
            $history,
            $request->question
        );

        /**
         * =========================
         * CALL LLM
         * =========================
         */
        try {
            $response = Http::timeout(30)
                ->withToken(config('rag.token'))
                ->asForm()
                ->post(config('rag.endpoint') . '/recommend', [
                    'prompt' => $prompt,
                ]);

            if (!$response->successful()) {
                throw new \Exception("HTTP " . $response->status());
            }

            $data = $response->json();

            $answer = $data['recommendation']
                ?? $data['recommendationn']
                ?? 'AI tidak memberikan jawaban';
        } catch (\Throwable $e) {
            $answer = "AI error: " . $e->getMessage();
        }

        /**
         * =========================
         * SAVE CHAT
         * =========================
         */
        $chat = ChatbotHistory::create([
            'user_id' => Auth::id(),
            'lahan_id' => $lahan->id,
            'session_id' => $sessionId,
            'question' => $request->question,
            'answer' => $answer,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'answer' => $chat->answer,
                'session_id' => $chat->session_id,
                'time' => $chat->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * =========================
     * PROMPT ENGINE (ANTI HALLUCINATION)
     * =========================
     */
    private function buildPrompt($lahan, $sensor, $weather, $history, $question): string
    {
        $prompt = "Anda adalah AI Smart Farming Assistant.\n";
        $prompt .= "WAJIB hanya gunakan data yang diberikan.\n";
        $prompt .= "Jika data tidak tersedia, jawab: 'Data tidak cukup'.\n\n";

        $prompt .= "=== LAHAN ===\n";
        $prompt .= "Nama: {$lahan->nama_lahan}\n";
        $prompt .= "Komoditas: " . ($lahan->komoditas->nama_komoditas ?? '-') . "\n\n";

        $prompt .= "=== SENSOR ===\n";

        if ($sensor) {
            $prompt .= "Moisture: {$sensor->humidity}\n";
            $prompt .= "pH: {$sensor->ph}\n";
            $prompt .= "Temperature: {$sensor->temperature}\n";
        } else {
            $prompt .= "Tidak ada data sensor\n";
        }

        $prompt .= "\n=== CUACA ===\n";
        $prompt .= "Temp: " . ($weather['temp'] ?? '--') . "\n";
        $prompt .= "Humidity: " . ($weather['humidity'] ?? '--') . "\n";
        $prompt .= "Condition: " . ($weather['condition'] ?? '--') . "\n\n";

        if ($history->isNotEmpty()) {
            $prompt .= "=== HISTORY ===\n";
            foreach ($history as $h) {
                $prompt .= "User: {$h->question}\n";
                $prompt .= "AI: {$h->answer}\n";
            }
        }

        $prompt .= "\n=== QUESTION ===\n{$question}\n";

        return $prompt;
    }

    /**
     * =========================
     * BMKG WEATHER (SAFE VERSION)
     * =========================
     */
    private function getWeatherData(string $kode): array
    {
        if (!$kode) {
            return [
                'temp' => '--',
                'humidity' => '--',
                'condition' => 'No location',
            ];
        }

        return cache()->remember("bmkg_{$kode}", 1800, function () use ($kode) {

            try {
                $response = Http::timeout(10)->get(
                    'https://api.bmkg.go.id/publik/prakiraan-cuaca',
                    ['adm4' => $kode]
                );

                if (!$response->successful()) {
                    throw new \Exception();
                }

                $data = $response->json();

                $forecast = $data['data'][0]['cuaca'][0][0] ?? null;

                if (!$forecast) {
                    throw new \Exception();
                }

                return [
                    'temp' => $forecast['t'] ?? '--',
                    'humidity' => $forecast['hu'] ?? '--',
                    'condition' => $forecast['weather_desc'] ?? '--',
                ];
            } catch (\Throwable $e) {
                return [
                    'temp' => '--',
                    'humidity' => '--',
                    'condition' => 'BMKG unavailable',
                ];
            }
        });
    }
}