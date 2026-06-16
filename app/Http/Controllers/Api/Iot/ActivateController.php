<?php

namespace App\Http\Controllers\Api\Iot;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivateController extends Controller
{
    public function activate(Request $request)
    {
        $request->validate([
            'device_uid' => 'required|string|max:64',
        ]);

        $device = Device::where('device_uid', $request->device_uid)->first(); 

        if (!$device || $device->isPending()) { 
            return response()->json(['status' => 'pending']); 
        }
 
        if ($device->isSuspended()) { 
            return response()->json(['message' => 'Device is suspended'], 403); 
        }
 
        $rawToken = 'sk_' . Str::random(48); 
 
        $device->update([ 
            'device_token_hash' => hash('sha256', $rawToken), 
            'token_retrieved_at' => now(),  
        ]);
 
        return response()->json([ 
            'status' => 'active', 
            'token' => $rawToken, 
        ]); 
    }
}