<?php

namespace App\Http\Controllers\Api\Iot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Iot\RegisterRequest;
use App\Models\Device;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $device = Device::where('device_uid', $request->device_uid)->first();

        if ($device) {
            return response()->json([
                'status' => $device->status,
                'message' => 'Device already registered',
            ]);
        }

        Device::create([
            'device_uid' => $request->device_uid,
            'device_name' => $request->device_name,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'pending',
            'message' => 'Device registered. Waiting for admin approval.',
        ], 201);
    }
}
