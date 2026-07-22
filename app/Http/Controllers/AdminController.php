<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lahan;
use App\Models\Device;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::withCount(['petani as lahan_count' => function ($query) {
            $query->leftJoin('lahan', 'petani.id', '=', 'lahan.petani_id')
                ->select(\DB::raw('count(lahan.id)'));
        }])->get();

        $lahanTotal = Lahan::count();
        $petaniCount = User::where('role', 'petani')->count();
        $deviceCount = Device::count();
        $pendingDeviceCount = Device::pending()->count();
        $onlineDeviceCount = Device::online()->count();

        $recentLahans = Lahan::withCount([
            'devices',
            'devices as online_count' => fn($q) => $q->online(),
        ])->with('petani.user:id,name', 'komoditas')->latest()->take(5)->get();

        $pendingDevices = Device::pending()->with('lahan:id,nama_lahan')
            ->latest()->take(10)->get();

        $lahansWithSensors = Lahan::with(['devices.latestReading', 'komoditas', 'petani.user'])
            ->withCount('devices')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($lahan) {
                $device = $lahan->devices->first();
                $reading = $device?->latestReading;
                $lahan->soil_ph = $reading?->ph ?? '--';
                $lahan->soil_moist = $reading?->humidity ?? '--';
                $lahan->last_update = $reading
                    ? Carbon::parse($reading->recorded_at)->format('H:i')
                    : '--';
                $lahan->luas_label = $lahan->hitungLuas()
                    ? number_format($lahan->hitungLuas(), 0, ',', '.') . ' m²'
                    : ($lahan->luas ? $lahan->luas . ' Ha' : '--');
                return $lahan;
            });

        $weatherData = $this->getWeatherData('32.73.12.1001');

        return view('admin.dashboard', compact(
            'users', 'lahanTotal', 'petaniCount',
            'deviceCount', 'pendingDeviceCount', 'onlineDeviceCount',
            'recentLahans', 'pendingDevices', 'lahansWithSensors', 'weatherData'
        ));
    }

    private function getWeatherData(string $kode): array
    {
        $locations = \App\Http\Controllers\LahanController::$lokasiWilayah;

        try {
            return Cache::remember("bmkg_admin_{$kode}", now()->addMinutes(30), function () use ($kode, $locations) {
                $response = Http::withoutVerifying()->timeout(10)->get(
                    'https://api.bmkg.go.id/publik/prakiraan-cuaca',
                    ['adm4' => $kode]
                );

                if ($response->successful()) {
                    $raw = $response->json();
                    $forecasts = $raw['data'][0]['cuaca'][0] ?? [];

                    if (!empty($forecasts)) {
                        $now = now()->format('Y-m-d H:i:s');
                        $currentMatch = null;

                        foreach ($forecasts as $forecast) {
                            if ($forecast['local_datetime'] >= $now) {
                                $currentMatch = $forecast;
                                break;
                            }
                        }

                        $data = $currentMatch ?: end($forecasts);

                        return [
                            'temp' => $data['t'] ?? '--',
                            'humidity' => $data['hu'] ?? '--',
                            'condition' => $data['weather_desc'] ?? 'N/A',
                            'area' => $locations[$kode] ?? ($raw['data'][0]['lokasi']['desa'] ?? 'Bandung'),
                            'source' => 'BMKG',
                        ];
                    }
                }

                throw new \Exception('Data tidak ditemukan di API BMKG');
            });
        } catch (\Exception $e) {
            return [
                'temp' => '--',
                'humidity' => '--',
                'condition' => 'Gagal sinkronisasi',
                'area' => $locations[$kode] ?? 'Bandung',
                'source' => 'BMKG (Offline)',
            ];
        }
    }

    public function users()
    {
        $users = User::withCount(['petani as lahan_count' => function ($query) {
            $query->leftJoin('lahan', 'petani.id', '=', 'lahan.petani_id')
                ->select(\DB::raw('count(lahan.id)'));
        }])->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function lahan()
    {
        $lahan = Lahan::with(['petani.user', 'komoditas', 'devices'])->withCount('devices')->paginate(20);

        return view('admin.lahan', compact('lahan'));
    }
}