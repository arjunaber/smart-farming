<?php

namespace App\Http\Controllers;

use App\Models\SiklusTanam;
use App\Models\Lahan;
use App\Models\MasterKomoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiklusTanamController extends Controller
{
    public function index()
    {
        $petani = Auth::user()->petani;
        $siklus = $petani
            ? SiklusTanam::with(['lahan', 'komoditas'])
            ->whereIn('lahan_id', $petani->lahan()->pluck('id'))
            ->latest()
            ->paginate(10)
            : collect();

        return view('siklus_tanam.index', compact('siklus'));
    }

    public function create()
    {
        $petani = Auth::user()->petani;
        if (!$petani) {
            return redirect()->back()->with('error', 'Profil petani tidak ditemukan.');
        }

        $lahanList = $petani->lahan()->get();
        $komoditasList = MasterKomoditas::all();

        return view('siklus_tanam.create', compact('lahanList', 'komoditasList'));
    }

    public function store(Request $request)
    {
        $petani = Auth::user()->petani;
        $lahanIds = $petani ? $petani->lahan()->pluck('id')->toArray() : [];

        $request->validate([
            'lahan_id' => 'required|in:' . implode(',', $lahanIds),
            'komoditas_id'   => 'required|exists:master_komoditas,id',
            'tanggal_mulai'  => 'required|date',
            'estimasi_panen' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Selesaikan siklus aktif sebelumnya di lahan yang sama (jika ada)
                SiklusTanam::where('lahan_id', $request->lahan_id)
                    ->where('status', 'aktif')
                    ->update(['status' => 'selesai']);

                // 2. Buat siklus baru
                SiklusTanam::create([
                    'lahan_id'       => $request->lahan_id,
                    'komoditas_id'   => $request->komoditas_id,
                    'tanggal_mulai'  => $request->tanggal_mulai,
                    'estimasi_panen' => $request->estimasi_panen,
                    'status'         => 'aktif'
                ]);

                // 3. Update status lahan
                Lahan::where('id', $request->lahan_id)->update(['status' => 'Aktif']);
            });

            return redirect()->route('siklus-tanam.index')->with('success', 'Siklus tanam berhasil dimulai!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}
