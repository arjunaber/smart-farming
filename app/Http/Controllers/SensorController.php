<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IotDevice;
use App\Models\EnvironmentalMetric;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data masuk dari hardware/mikrokontroler
        $validated = $request->validate([
            'serial_number'   => 'required|string', // Menggunakan nomor seri hardware unik alat IoT
            'temperature'     => 'required|numeric',
            'humidity'        => 'required|numeric', // Kelembapan udara sekitar
            'ph'   => 'required|numeric', // Kelembapan tanah
            'light_intensity' => 'required|numeric', // Intensitas cahaya (lux)
        ]);

        try {
            // 2. Cari perangkat IoT terdaftar berdasarkan nomor seri hardware
            $device = IotDevice::where('serial_number', $validated['serial_number'])->first();

            if (!$device) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Perangkat IoT tidak terdaftar di sistem.'
                ], 404);
            }

            // 3. Simpan metrik lingkungan menggunakan model Eloquent baru
            $metric = EnvironmentalMetric::create([
                'iot_device_id'   => $device->id,
                'recorded_at'     => now(),
                'temperature'     => $validated['temperature'],
                'humidity'        => $validated['humidity'],
                'ph'   => $validated['ph'],
                'light_intensity' => $validated['light_intensity'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data Metrik IoT Berhasil Disimpan',
                'data' => [
                    'device_name' => $device->device_name,
                    'metric_id'   => $metric->id
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
