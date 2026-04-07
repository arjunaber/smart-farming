<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TanamanController extends Controller
{
    public function index()
    {
        $tanaman = Tanaman::all()->map(function ($item) {

            if ($item->durasi_min && $item->durasi_max) {

                $tgl_tanam = Carbon::parse($item->tanggal_tanam);
                $tgl_panen_min = $tgl_tanam->copy()->addDays($item->durasi_min);
                $tgl_panen_max = $tgl_tanam->copy()->addDays($item->durasi_max);

                // Format tampilan
                $item->prediksi_panen = 'Tercepat: ' . $tgl_panen_min->format('d M Y');

                // Untuk countdown pakai tercepat
                $item->panen_iso = $tgl_panen_min->toIso8601String();

                // Progress pakai durasi MAX (lebih realistis)
                $hari_berjalan = max(0, $tgl_tanam->diffInDays(now(), false));
                $item->progress = min(100, round(($hari_berjalan / $item->durasi_max) * 100));

                // Bonus (kalau mau dipakai di frontend)
                $item->panen_tercepat = $tgl_panen_min->format('d M Y');
                $item->panen_terlama  = $tgl_panen_max->format('d M Y');
            } else {
                $item->prediksi_panen = 'Menghitung...';
                $item->panen_iso = null;
                $item->progress = 0;
            }

            return $item;
        });

        return view('growth', compact('tanaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tanaman' => 'required',
            'tanggal_tanam' => 'required|date',
            'durasi_min' => 'required|integer',
            'durasi_max' => 'required|integer'
        ]);

        Tanaman::create([
            'nama_tanaman' => $request->nama_tanaman,
            'tanggal_tanam' => $request->tanggal_tanam,
            'durasi_min' => $request->durasi_min,
            'durasi_max' => $request->durasi_max,
        ]);

        return redirect()->back()->with('success', 'Data tanaman berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_tanam' => 'required|date'
        ]);

        $tanaman = Tanaman::findOrFail($id);

        $tanaman->update([
            'tanggal_tanam' => $request->tanggal_tanam
        ]);

        return redirect()->back()->with('success', 'Tanggal tanam berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tanaman = Tanaman::findOrFail($id);
        $tanaman->delete();

        return redirect()->back()->with('success', 'Data tanaman berhasil dihapus!');
    }
}
