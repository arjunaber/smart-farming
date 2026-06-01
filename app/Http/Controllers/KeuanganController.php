<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    /**
     * Menampilkan halaman utama (tabel dan rekap data dinamis)
     */
    public function index()
    {
        // 1. Mengambil semua data keuangan beserta relasi lahannya (Eager Loading)
        $keuangan = Keuangan::with('lahan')->orderBy('tanggal', 'desc')->get();

        // 2. Menghitung total seluruh pengeluaran secara dinamis
        $totalPengeluaran = Keuangan::sum('nominal');

        // 3. Menghitung beban berdasarkan kategori spesifik untuk rekap card di FE
        $bebanPupuk = Keuangan::where('kategori', 'Pupuk')->sum('nominal');
        $bebanObatHama = Keuangan::where('kategori', 'Obat Hama')->sum('nominal');

        // Mengirimkan semua data ke view index keuangan
        return view('keuangan.index', compact(
            'keuangan',
            'totalPengeluaran',
            'bebanPupuk',
            'bebanObatHama'
        ));
    }

    /**
     * Menampilkan form tambah pengeluaran dengan data lahan asli
     */
    public function create()
    {
        // Mengambil semua data lahan dari database untuk mengisi dropdown pilihan lahan
        $lahan = Lahan::all();

        return view('keuangan.create', compact('lahan'));
    }

    /**
     * Menyimpan data pengeluaran asli ke database
     */
    public function store(Request $request)
    {
        // Validasi input data dari form FE
        $request->validate([
            'lahan_id'   => 'required|exists:lahan,id', // Memastikan ID lahan valid di tabel lahan
            'kategori'   => 'required|string',
            'nominal'    => 'required|numeric|min:0',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Menyimpan data ke dalam tabel 'keuangan' menggunakan mass assignment
        Keuangan::create([
            'lahan_id'   => $request->lahan_id,
            'kategori'   => $request->kategori,
            'nominal'    => $request->nominal,
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        // Redirect dengan session flash message untuk memicu notifikasi SweetAlert di FE
        return redirect()->route('keuangan.index')
            ->with('success', 'Data pengeluaran berhasil dicatat!');
    }

    /**
     * Menghapus data pengeluaran berdasarkan ID dari database
     */
    public function destroy($id)
    {
        // Mencari data atau langsung memunculkan error 404 jika ID tidak ditemukan
        $keuangan = Keuangan::findOrFail($id);

        // Proses hapus data
        $keuangan->delete();

        // Redirect kembali ke halaman utama dengan pesan sukses
        return redirect()->route('keuangan.index')
            ->with('success', 'Data pengeluaran berhasil dihapus!');
    }
}