<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // Pastikan ini sudah di-import
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Mapping menggunakan Kode Wilayah ADM4 terbaru
        $locations = [
            '32.75.01.1001' => 'Kota Bekasi (Bekasi Jaya)',
            '32.73.12.1001' => 'Kota Bandung (Citarum)',
            '32.04.13.2001' => 'Kab. Bandung (Bojongsoang)', // Wilayah Kosanmu
            '31.71.01.1001' => 'Jakarta Pusat (Gambir)',
        ];

        $selectedId = $request->get('location', session('selected_location', '32.04.13.2001'));
        session(['selected_location' => $selectedId]);

        try {
            $cacheKey = "bmkg_weather_v2_{$selectedId}";

            $weatherData = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($selectedId, $locations) {

                // LOG 1: Mencatat kapan server benar-benar request ke API BMKG
                Log::info("Menarik data cuaca BARU dari API BMKG untuk area: " . ($locations[$selectedId] ?? $selectedId));

                $response = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Laravel/Dashboard',
                    'Accept' => 'application/json',
                ])
                    ->timeout(10)
                    ->withoutVerifying()
                    ->get("https://api.bmkg.go.id/publik/prakiraan-cuaca", [
                        'adm4' => $selectedId
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $forecast = null;

                    // Algoritma mencari data cuaca yang waktunya paling dekat dengan waktu saat ini
                    if (isset($json['data'][0]['cuaca'])) {
                        $now = now()->timezone('Asia/Jakarta');
                        $closestDiff = null;

                        foreach ($json['data'][0]['cuaca'] as $hari) {
                            foreach ($hari as $jam) {
                                if (isset($jam['local_datetime'])) {
                                    $time = Carbon::createFromFormat('Y-m-d H:i:s', $jam['local_datetime'], 'Asia/Jakarta');
                                    $diff = abs($now->diffInMinutes($time));

                                    // Cari selisih waktu terkecil (paling relevan dengan saat ini)
                                    if ($closestDiff === null || $diff < $closestDiff) {
                                        $closestDiff = $diff;
                                        $forecast = $jam;
                                    }
                                }
                            }
                        }
                    }

                    // Fallback jika loop di atas gagal (ambil data hari pertama, jam pertama)
                    if (!$forecast && isset($json['data'][0]['cuaca'][0][0])) {
                        $forecast = $json['data'][0]['cuaca'][0][0];
                    }

                    if ($forecast) {
                        return [
                            'temp'      => $forecast['t'] ?? '27',
                            'humidity'  => $forecast['hu'] ?? '75',
                            'condition' => $forecast['weather_desc'] ?? 'Berawan',
                            'area'      => $locations[$selectedId] ?? 'Tidak Diketahui',
                            'is_live'   => true
                        ];
                    }
                }

                // Lempar error jika gagal agar ditangkap catch blok dan tidak masuk Cache
                throw new \Exception("BMKG Server Error: " . $response->status());
            });
        } catch (\Exception $e) {
            Log::error("BMKG API v2 Error: " . $e->getMessage());
            $weatherData = [
                'temp'      => '27',
                'humidity'  => '75',
                'condition' => 'Data Terbatas',
                'area'      => ($locations[$selectedId] ?? 'Lokasi') . ' (Offline)',
                'is_live'   => false
            ];
        }

        // --- TAMBAHAN LOG 2: Mengecek status is_live dari variabel $weatherData ---
        if (isset($weatherData['is_live']) && $weatherData['is_live'] === true) {
            Log::info("Memuat Dashboard: Status Cuaca LIVE (Area: {$weatherData['area']}, Suhu: {$weatherData['temp']}°C, Cuaca: {$weatherData['condition']}).");
        } else {
            Log::warning("Memuat Dashboard: Status Cuaca OFFLINE/Fallback (Area: {$weatherData['area']}).");
        }
        // -------------------------------------------------------------------------

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($weatherData);
        }

        return view('dashboard', array_merge($weatherData, [
            'humidity_api'     => $weatherData['humidity'],
            'cuaca'            => $weatherData['condition'],
            'locations'        => $locations,
            'selectedLocation' => $selectedId,
            'showModal'        => !session()->has('has_seen_modal')
        ]));
    }

    public function setSeen()
    {
        session(['has_seen_modal' => true]);
        return response()->json(['status' => 'ok']);
    }
}
