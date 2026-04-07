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
        $locations = [
            // --- KOTA BANDUNG (32.73) ---
            '32.73.12.1001' => 'Kota Bandung (Citarum)',
            '32.73.05.1001' => 'Kota Bandung (Antapani)',
            '32.73.20.1001' => 'Kota Bandung (Arcamanik)',
            '32.73.01.1001' => 'Kota Bandung (Andir)',
            '32.73.08.1001' => 'Kota Bandung (Astana Anyar)',
            '32.73.04.1001' => 'Kota Bandung (Babakan Ciparay)',
            '32.73.22.1001' => 'Kota Bandung (Bandung Kidul)',
            '32.73.03.1001' => 'Kota Bandung (Bandung Kulon)',
            '32.73.13.1001' => 'Kota Bandung (Bandung Wetan)',
            '32.73.11.1001' => 'Kota Bandung (Batununggal)',
            '32.73.09.1001' => 'Kota Bandung (Bojongloa Kaler)',
            '32.73.10.1001' => 'Kota Bandung (Bojongloa Kidul)',
            '32.73.23.1001' => 'Kota Bandung (Buahbatu)',
            '32.73.15.1001' => 'Kota Bandung (Cibeunying Kaler)',
            '32.73.14.1001' => 'Kota Bandung (Cibeunying Kidul)',
            '32.73.25.1001' => 'Kota Bandung (Cibiru)',
            '32.73.02.1001' => 'Kota Bandung (Cicendo)',
            '32.73.18.1001' => 'Kota Bandung (Cidadap)',
            '32.73.29.1001' => 'Kota Bandung (Cinambo)',
            '32.73.16.1001' => 'Kota Bandung (Coblong)',
            '32.73.26.1001' => 'Kota Bandung (Gedebage)',
            '32.73.19.1001' => 'Kota Bandung (Kiaracondong)',
            '32.73.17.1001' => 'Kota Bandung (Lengkong)',
            '32.73.30.1001' => 'Kota Bandung (Mandalajati)',
            '32.73.28.1001' => 'Kota Bandung (Panyileukan)',
            '32.73.24.1001' => 'Kota Bandung (Rancasari)',
            '32.73.07.1001' => 'Kota Bandung (Regol)',
            '32.73.06.1001' => 'Kota Bandung (Sukajadi)',
            '32.73.21.1001' => 'Kota Bandung (Sukasari)',
            '32.73.27.1001' => 'Kota Bandung (Ujung Berung)',

            // --- KABUPATEN BANDUNG (32.04) ---
            '32.04.13.2001' => 'Kab. Bandung (Bojongsoang)',
            '32.04.14.1001' => 'Kab. Bandung (Dayeuhkolot)',
            '32.04.12.1001' => 'Kab. Bandung (Baleendah)',
            '32.04.34.2001' => 'Kab. Bandung (Soreang)',
            '32.04.16.2001' => 'Kab. Bandung (Banjaran)',
            '32.04.28.2001' => 'Kab. Bandung (Cileunyi)',
            '32.04.29.2001' => 'Kab. Bandung (Cimenyan)',
            '32.04.09.2001' => 'Kab. Bandung (Pangalengan)',
            '32.04.30.2001' => 'Kab. Bandung (Cilengkrang)',
            '32.04.27.2001' => 'Kab. Bandung (Rancaekek)',
            '32.04.32.2001' => 'Kab. Bandung (Majalaya)',
            '32.04.11.2001' => 'Kab. Bandung (Ciparay)',

            // --- KABUPATEN BANDUNG BARAT (32.17) ---
            '32.17.03.2001' => 'Kab. Bandung Barat (Lembang)',
            '32.17.01.2001' => 'Kab. Bandung Barat (Ngamprah)',
            '32.17.02.2001' => 'Kab. Bandung Barat (Padalarang)',
            '32.17.13.2001' => 'Kab. Bandung Barat (Parongpong)',

            // --- WILAYAH JABAR LAINNYA ---
            '32.75.01.1001' => 'Kota Bekasi (Bekasi Jaya)',
            '32.16.01.2001' => 'Kab. Bekasi (Cikarang Pusat)',
            '32.71.01.1001' => 'Kota Bogor (Bogor Timur)',
            '32.01.01.2001' => 'Kab. Bogor (Cibinong)',
            '32.77.01.1001' => 'Kota Cimahi (Cimahi)',
            '32.72.01.1001' => 'Kota Sukabumi (Cikole)',
            '32.05.01.2001' => 'Kab. Garut (Tarogong Kidul)',
            '32.78.01.1001' => 'Kota Tasikmalaya (Tawang)',
            '32.74.01.1001' => 'Kota Cirebon (Kejaksan)',
        ];


        $selectedId = $request->get('location', session('selected_location', '32.04.13.2001'));
        session(['selected_location' => $selectedId]);

        try {
            $cacheKey = "bmkg_v2_{$selectedId}";
            $weatherData = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($selectedId, $locations) {
                $response = Http::timeout(10)->withoutVerifying()
                    ->get("https://api.bmkg.go.id/publik/prakiraan-cuaca", ['adm4' => $selectedId]);

                if ($response->successful()) {
                    $json = $response->json();
                    $dataCuaca = $json['data'][0]['cuaca'][0][0] ?? null;

                    if ($dataCuaca) {
                        return [
                            'temp'      => $dataCuaca['t'] ?? '27',
                            'humidity'  => $dataCuaca['hu'] ?? '75',
                            'condition' => $dataCuaca['weather_desc'] ?? 'Berawan',
                            'area'      => $locations[$selectedId] ?? 'Jawa Barat',
                            'is_live'   => true
                        ];
                    }
                }
                throw new \Exception("Gagal fetch BMKG");
            });
        } catch (\Exception $e) {
            $weatherData = [
                'temp' => '27',
                'humidity' => '75',
                'condition' => 'Data Terbatas',
                'area' => ($locations[$selectedId] ?? 'Lokasi') . ' (Offline)',
                'is_live' => false
            ];
        }

        // Log untuk tracking Live Data
        Log::info("Dashboard Access - Area: {$weatherData['area']} | Live: " . ($weatherData['is_live'] ? 'YES' : 'NO'));

        if ($request->ajax()) {
            return response()->json($weatherData);
        }

        return view('dashboard', array_merge($weatherData, [
            'locations' => $locations,
            'selectedLocation' => $selectedId
        ]));
    }

    public function setSeen()
    {
        session(['has_seen_modal' => true]);
        return response()->json(['status' => 'ok']);
    }
}