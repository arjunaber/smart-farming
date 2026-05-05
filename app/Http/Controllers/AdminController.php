<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Middleware di route


    public function dashboard()
    {
        $users = User::withCount('lahan')->get();
        $lahanTotal = Lahan::count();
        $petaniCount = User::where('role', 'petani')->count();
        return view('admin.dashboard', compact('users', 'lahanTotal', 'petaniCount'));
    }

    public function users()
    {
        $users = User::withCount('lahan')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function lahan()
    {
        $lahan = Lahan::with('user')->paginate(20);
        return view('admin.lahan', compact('lahan'));
    }
}

