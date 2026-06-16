<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;

class CheckDeviceToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Device token not provided'], 401);
        }

        
        $hash = hash('sha256', $token);
        $device = Device::where('device_token_hash', $hash)
            ->where('status', 'active')
            ->first();

        if (!$device) {
            return response()->json(['message' => 'Invalid or inactive device token'], 401);
        }

        $request->merge(['device' => $device]);

        return $next($request);
    }
}