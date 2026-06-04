<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lahan;
use App\Models\Device;
use Illuminate\Http\Request;

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
        ])->with('petani.user:id,name')->latest()->take(5)->get();

        $pendingDevices = Device::pending()->with('lahan:id,nama_lahan')
            ->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'users', 'lahanTotal', 'petaniCount',
            'deviceCount', 'pendingDeviceCount', 'onlineDeviceCount',
            'recentLahans', 'pendingDevices'
        ));
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
        $lahan = Lahan::with('petani.user')->paginate(20);

        return view('admin.lahan', compact('lahan'));
    }
}