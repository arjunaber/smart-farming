<?php

namespace App\Http\Controllers;

use App\Models\ChatbotHistory;
use App\Models\Lahan;
use App\Models\EnvironmentalMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
        $petani = Auth::user()->petani;
        $lahanList = $petani ? $petani->lahan : collect();

        $selectedLahanId = $request->get('lahan_id') ?? ($lahanList->first()->id ?? null);

        // Ambil riwayat percakapan chat berdasarkan lahan yang dipilih
        $chats = ChatbotHistory::where('user_id', Auth::id())
            ->where('lahan_id', $selectedLahanId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chatbot.index', compact('lahanList', 'chats', 'selectedLahanId'));
    }

    public function ask(Request $request)
    {
        $request->validate([
            'lahan_id' => 'required|exists:lahan,id',
            'question' => 'required|string'
        ]);

        $lahan = Lahan::find($request->lahan_id);

        // 1. Ambil metrik IoT teranyar di lahan ini sebagai injeksi context kecerdasan buatan (LLM)
        $latestMetric = EnvironmentalMetric::whereHas('iotDevice', function ($q) use ($lahan) {
            $q->where('lahan_id', $lahan->id);
        })->latest('recorded_at')->first();

        // 2. Susun prompt agar respons LLM akurat dan tidak melantur keluar konteks
        $contextPrompt = "Anda adalah sistem pakar AI pertanian pintar AGA Smart Farming.\n";
        $contextPrompt .= "Konteks Lahan Saat Ini:\n";
        $contextPrompt .= "- Nama Lahan: {$lahan->nama_lahan}\n";

        if ($latestMetric) {
            $contextPrompt .= "- Suhu Udara Perangkat: {$latestMetric->temperature}°C\n";
            $contextPrompt .= "- Kelembapan Udara Perangkat: {$latestMetric->humidity}%\n";
            $contextPrompt .= "- Kelembapan Tanah: {$latestMetric->ph}%\n";
            $contextPrompt .= "- Intensitas Cahaya: {$latestMetric->light_intensity} lux\n";
        } else {
            $contextPrompt .= "- Data metrik sensor IoT lapangan belum tersedia.\n";
        }

        $contextPrompt .= "\nPertanyaan Petani: " . $request->question;

        try {
            // 3. Tembak ke API LLM (Contoh menggunakan Gemini API / OpenAI / Lokal Ollama)
            // Silakan sesuaikan URL endpoint dan API_KEY milik kelompokmu di file .env
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('LLM_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post(env('LLM_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions'), [
                'model' => 'gpt-4o-mini', // atau gemini-1.5-flash sesuai integrasi tim
                'messages' => [
                    ['role' => 'user', 'content' => $contextPrompt]
                ],
                'temperature' => 0.5,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $answer = $result['choices'][0]['message']['content'] ?? 'Maaf, sistem tidak dapat memproses jawaban.';
            } else {
                $answer = "Maaf, sistem AI sedang sibuk. Silakan coba beberapa saat lagi.";
            }
        } catch (\Exception $e) {
            $answer = "Terjadi kegagalan koneksi ke server kecerdasan buatan: " . $e->getMessage();
        }

        // 4. Simpan hasilnya ke database agar riwayat chat tidak hilang saat direfresh
        $chatRecord = ChatbotHistory::create([
            'user_id'  => Auth::id(),
            'lahan_id' => $lahan->id,
            'question' => $request->question,
            'answer'   => $answer
        ]);

        return redirect()->back()->with('success', 'Pesan terkirim.');
    }
}
