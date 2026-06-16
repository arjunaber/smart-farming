<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Lahan;
use App\Models\SensorReading;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userLahan = $user->petani ? $user->petani->lahan()->with('komoditas')->get() : collect();

        $selectedLahanId = $request->get('lahan_id');
        $lahan = $selectedLahanId ? $userLahan->find($selectedLahanId) : $userLahan->first();

        if (!$lahan) {
            return view('dashboard', $this->emptyState());
        }

        $device    = $lahan->devices()->first();
        $needsSync = !$device;
        $sensorData = $device?->latestReading;

        $kodeWilayah = $lahan->lokasi;
        $weatherData = $this->getWeatherData($kodeWilayah);

        // ↓ Hitung luas dari polygon_coordinates jika hitungLuas() belum ada di model
        $luasLahan = method_exists($lahan, 'hitungLuas')
            ? $lahan->hitungLuas()
            : $this->hitungLuasPolygon($lahan->polygon_coordinates ?? []);


        return view('dashboard', array_merge($weatherData, [
            'lahan'          => $lahan,
            'userLahan'      => $userLahan,
            'needsSync'      => $needsSync,
            'soil_ph'        => $sensorData ? $sensorData->ph       : '--',
            'soil_moist'     => $sensorData ? $sensorData->humidity  : '--',
            'last_update'    => $sensorData
                ? Carbon::parse($sensorData->recorded_at)->format('H:i')
                : Carbon::now()->format('H:i'),
            'luas_lahan'     => $luasLahan,
            'komoditas_nama' => $lahan->komoditas->nama_komoditas ?? 'N/A',
        ]));
    }

    /**
     * Fallback hitung luas polygon (Shoelace formula) dalam m²
     * jika method hitungLuas() belum ada di Model Lahan.
     *
     * @param  array<int, array<int, float>>  $coords  [[lat,lng], ...]
     */
    private function hitungLuasPolygon(array $coords): float
    {
        $n = count($coords);
        if ($n < 3) {
            return 0.0;
        }

        // Konversi kasar lat/lng → meter (1° ≈ 111_320 m)
        $toM = fn(array $c): array => [
            $c[1] * 111_320 * cos(deg2rad($c[0])), // x (lng → m)
            $c[0] * 111_320,                        // y (lat → m)
        ];

        $points = array_map($toM, $coords);
        $area   = 0.0;

        for ($i = 0; $i < $n; $i++) {
            $j     = ($i + 1) % $n;
            $area += $points[$i][0] * $points[$j][1];
            $area -= $points[$j][0] * $points[$i][1];
        }

        return abs($area / 2.0);
    }

    private function getWeatherData(string $kode): array
    {
        $locations = LahanController::$lokasiWilayah;

        try {
            return Cache::remember("bmkg_actual_{$kode}", now()->addMinutes(30), function () use ($kode, $locations) {
                $response = Http::withoutVerifying()->timeout(10)->get(
                    'https://api.bmkg.go.id/publik/prakiraan-cuaca',
                    ['adm4' => $kode]
                );

                if ($response->successful()) {
                    $raw       = $response->json();
                    $forecasts = $raw['data'][0]['cuaca'][0] ?? [];


                    if (!empty($forecasts)) {
                        $now          = now()->format('Y-m-d H:i:s');
                        $currentMatch = null;

                        foreach ($forecasts as $forecast) {
                            if ($forecast['local_datetime'] >= $now) {
                                $currentMatch = $forecast;
                                break;
                            }
                        }

                        $data = $currentMatch ?: end($forecasts);

                        return [
                            'temp'      => $data['t']            ?? '--',
                            'humidity'  => $data['hu']           ?? '--',
                            'condition' => $data['weather_desc'] ?? 'N/A',
                            'area'      => $locations[$kode]     ?? ($raw['data'][0]['lokasi']['desa'] ?? 'Lokasi Terdaftar'),
                            'source'    => 'BMKG',
                            'last_sync' => $data['local_datetime'] ?? null,
                        ];
                    }
                }

                throw new \Exception('Data tidak ditemukan di API BMKG');
            });
        } catch (\Exception $e) {
            return [
                'temp'      => '--',
                'humidity'  => '--',
                'condition' => 'Gagal sinkronisasi',
                'area'      => $locations[$kode] ?? 'N/A',
                'source'    => 'BMKG (Offline)',
            ];
        }
    }

    private function emptyState(): array
    {
        return [
            'lahan'       => null,
            'userLahan'   => collect(),
            'temp'        => '--',
            'humidity'    => '--',
            'condition'   => 'N/A',
            'area'        => 'N/A',
            'soil_ph'     => '--',
            'soil_moist'  => '--',
            'last_update' => '--',
            'needsSync'   => false,
            'luas_lahan'  => 0,
        ];
    }
}