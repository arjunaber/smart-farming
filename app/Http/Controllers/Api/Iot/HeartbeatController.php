<?php

namespace App\Http\Controllers\Api\Iot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    public function heartbeat(Request $request)
    {
        $device = $request->get('device');
        $device->update(['last_seen' => now()]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Heartbeat received',
        ]);
    }
}
