<?php

namespace App\Http\Controllers;

use App\Models\IotDevice;
use App\Models\Lahan;
use App\Models\EnvironmentalMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IotDeviceController extends Controller
{
    public function index()
    {
        $petani = Auth::user()->petani;
        $lahanIds = $petani ? $petani->lahan()->pluck('id') : collect();

        // Mengambil seluruh perangkat IoT yang terpasang di lahan milik petani
        $devices = IotDevice::with('lahan')
            ->whereIn('lahan_id', $lahanIds)
            ->latest()
            ->paginate(10);

        return view('iot_devices.index', compact('devices'));
    }

    public function create()
    {
        $petani = Auth::user()->petani;
        $lahanList = $petani ? $petani->lahan : collect();

        return view('iot_devices.create', compact('lahanList'));
    }

    public function store(Request $request)
    {
        $petani = Auth::user()->petani;
        $lahanIds = $petani ? $petani->lahan()->pluck('id')->toArray() : [];

        $request->validate([
            'lahan_id' => [
                'required',
                function ($attribute, $value, $fail) use ($lahanIds) {
                    if (!in_array($value, $lahanIds)) {
                        $fail('Lahan tidak valid.');
                    }
                }
            ],
            'device_name'   => 'required|string|max:255',
            'device_type'   => 'required|string|max:100', // misal: Monitoring Tanah, Smart Trash Bin
            'serial_number' => 'required|string|unique:iot_devices,serial_number',
        ]);

        IotDevice::create(array_merge($request->all(), ['status' => 'aktif']));

        return redirect()->route('iot-devices.index')->with('success', 'Perangkat IoT berhasil didaftarkan!');
    }

    public function show(IotDevice $iotDevice)
    {
        $petani = Auth::user()->petani;
        if (!$petani || $iotDevice->lahan->petani_id !== $petani->id) {
            abort(403);
        }

        // Ambil 30 data metrik sensor terbaru untuk kebutuhan grafik di View Chart.js
        $metrics = EnvironmentalMetric::where('iot_device_id', $iotDevice->id)
            ->latest('recorded_at')
            ->take(30)
            ->get()
            ->reverse(); // Di-reverse agar urutan waktu grafik berjalan maju (kiri ke kanan)

        return view('iot_devices.show', compact('iotDevice', 'metrics'));
    }
}
