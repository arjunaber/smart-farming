<?php

namespace App\Http\Controllers\Api\Iot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Iot\ReadingRequest;
use App\Models\SensorReading;

class ReadingController extends Controller
{
    public function store(ReadingRequest $request)
    {
        $device = $request->get('device');

        $reading = SensorReading::create([
            'iot_device_id' => $device->id,
            'ph' => $request->ph,
            'humidity' => $request->humidity,
            'recorded_at' => now(),
        ]);

        $device->update(['last_seen' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Sensor data stored',
            'data' => [
                'reading_id' => $reading->id,
            ],
        ], 201);
    }
}
