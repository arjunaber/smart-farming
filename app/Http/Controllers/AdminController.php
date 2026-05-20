<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lahan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Mengambil user dengan jumlah lahan melalui relasi petani
        $users = User::withCount(['petani as lahan_count' => function ($query) {
            $query->leftJoin('lahan', 'petani.id', '=', 'lahan.petani_id')
                ->select(\DB::raw('count(lahan.id)'));
        }])->get();

        $lahanTotal = Lahan::count();
        $petaniCount = User::where('role', 'petani')->count();

        return view('admin.dashboard', compact('users', 'lahanTotal', 'petaniCount'));
    }

    public function users()
    {
        // Mengambil user beserta hitungan total lahan melalui tabel petani
        $users = User::withCount(['petani as lahan_count' => function ($query) {
            $query->leftJoin('lahan', 'petani.id', '=', 'lahan.petani_id')
                ->select(\DB::raw('count(lahan.id)'));
        }])->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function lahan()
    {
        // Relasi berantai dari Lahan -> Petani -> User
        $lahan = Lahan::with('petani.user')->paginate(20);

        return view('admin.lahan', compact('lahan'));
    }
}
