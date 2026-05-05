<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LahanController extends Controller
{
    public function index()
    {
        $lahan = Auth::user()->lahan()->with('logbookEntries')->get();
        return view('lahan.index', compact('lahan'));
    }

    public function create()
    {
        $komoditasList = [
            'Padi IR64' => 'Padi IR64',
            'Jahe Merah' => 'Jahe Merah',
            'Kunyit' => 'Kunyit',
            'Temulawak' => 'Temulawak',
        ];
        return view('lahan.create', compact('komoditasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'luas' => 'required|numeric|min:0.01',
            'lokasi' => 'required|string|max:255',
            'komoditas' => 'required|array|min:1',
            'komoditas.*.nama' => 'required|string',
            'komoditas.*.fase' => 'required|string',
        ]);

        $lahan = Auth::user()->lahan()->create($request->only(['nama_lahan', 'luas', 'lokasi', 'komoditas', 'kesesuaian_score', 'deskripsi']));

        return redirect()->route('lahan.index')->with('success', 'Lahan baru ditambahkan!');
    }

    public function show(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            abort(403);
        }
        $lahan->load('logbookEntries');
        return view('lahan.show', compact('lahan'));
    }

    public function edit(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            abort(403);
        }
        return view('lahan.edit', compact('lahan'));
    }

    public function update(Request $request, Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_lahan' => 'required|string|max:255',
            'luas' => 'required|numeric|min:0.01',
        ]);

        $lahan->update($request->only(['nama_lahan', 'luas', 'lokasi', 'komoditas', 'kesesuaian_score', 'deskripsi']));

        return redirect()->route('lahan.index')->with('success', 'Lahan diperbarui!');
    }

    public function destroy(Lahan $lahan)
    {
        if ($lahan->user_id !== Auth::id()) {
            abort(403);
        }
        $lahan->delete();
        return redirect()->route('lahan.index')->with('success', 'Lahan dihapus!');
    }
}

