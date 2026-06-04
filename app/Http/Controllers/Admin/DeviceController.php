<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Lahan;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all'); // all | pending | active | suspended
        $search = $request->get('q');

        $query = Device::with(['lahan.petani.user'])
            ->when(
                $search,
                fn($q) => $q
                    ->where('device_uid', 'like', "%{$search}%")
                    ->orWhere('device_name', 'like', "%{$search}%")
            )
            ->when($status === 'pending',   fn($q) => $q->pending())
            ->when($status === 'active',    fn($q) => $q->active())
            ->when($status === 'suspended', fn($q) => $q->suspended())
            ->latest();

        $devices = $query->paginate(15)->withQueryString();

        $counts = [
            'all'       => Device::count(),
            'pending'   => Device::pending()->count(),
            'active'    => Device::active()->count(),
            'suspended' => Device::suspended()->count(),
        ];

        $lahans = Lahan::with('petani.user:id,name')->get();

        return view('admin.iot-devices', compact('devices', 'counts', 'lahans'));
    }

    public function approve(Request $request, Device $device)
    {
        if (!$device->isPending()) {
            return back()->with('error', 'Only pending devices can be approved.');
        }

        $validated = $request->validate([
            'lahan_id' => 'required|exists:lahan,id',
        ]);

        $device->update([
            'lahan_id'    => $validated['lahan_id'],
            'status'      => 'active',
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            "Device {$device->device_uid} approved and assigned to lahan."
        );
    }

    public function suspend(Device $device)
    {
        if (!$device->isActive()) {
            return back()->with('error', 'Only active devices can be suspended.');
        }

        $device->update(['status' => 'suspended']);

        return back()->with('success', "Device {$device->device_uid} suspended.");
    }

    public function reset(Device $device)
    {
        $device->update([
            'lahan_id'            => null,
            'status'              => 'pending',
            'device_token_hash'   => null,
            'approved_at'         => null,
            'token_retrieved_at'  => null,
        ]);

        return back()->with('success', "Device {$device->device_uid} reset to pending.");
    }
}