<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Lahan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua daftar lahan milik user untuk dropdown
        $userLahan = Auth::user()->lahan()->get();

        // 2. Ambil daftar nama wilayah (untuk mapping kode ADM4 ke nama Kecamatan)
        $locations = \App\Http\Controllers\LahanController::$lokasiWilayah;

        // 3. Tentukan Lahan yang sedang dilihat (Selected Lahan)
        $selectedLahanId = $request->get('lahan_id');

        if ($selectedLahanId) {
            $lahan = Auth::user()->lahan()->find($selectedLahanId);
        } else {
            $lahan = $userLahan->first();
        }

        // 4. Fallback jika user belum punya lahan sama sekali
        if (!$lahan) {
            return view('dashboard', [
                'lahan' => null,
                'userLahan' => collect(),
                'temp' => '--',
                'humidity' => '--',
                'condition' => 'Lahan Tidak Ditemukan',
                'area' => 'N/A',
                'last_update' => Carbon::now()->format('H:i')
            ]);
        }

        // 5. Otomatis ambil lokasi dari atribut lahan
        $selectedLocationId = $lahan->lokasi; // Kode ADM4 dari DB

        // 6. Fetch Data Cuaca berdasarkan lokasi lahan tersebut
        try {
            $cacheKey = "bmkg_v2_{$selectedLocationId}";
            $weatherData = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($selectedLocationId, $locations) {
                $response = Http::timeout(10)
                    ->withoutVerifying()
                    ->get('https://api.bmkg.go.id/publik/prakiraan-cuaca', [
                        'adm4' => $selectedLocationId
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $dataCuaca = $json['data'][0]['cuaca'][0][0] ?? null;

                    if ($dataCuaca) {
                        return [
                            'temp'      => $dataCuaca['t'] ?? '27',
                            'humidity'  => $dataCuaca['hu'] ?? '75',
                            'condition' => $dataCuaca['weather_desc'] ?? 'Berawan',
                            'area'      => $locations[$selectedLocationId] ?? 'Jawa Barat',
                        ];
                    }
                }
                throw new \Exception('API BMKG Error');
            });
        } catch (\Exception $e) {
            Log::warning("Weather Error: " . $e->getMessage());
            $weatherData = [
                'temp' => '27',
                'humidity' => '75',
                'condition' => 'Offline',
                'area' => $locations[$selectedLocationId] ?? 'Lokasi'
            ];
        }

        return view('dashboard', array_merge($weatherData, [
            'lahan'     => $lahan,
            'userLahan' => $userLahan,
            'last_update' => Carbon::now()->format('H:i')
        ]));
    }
}
