<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    /**
     * Menampilkan halaman utama (tabel dan rekap)
     */
    public function index()
    {
        // Variabel dummy agar tidak error di Blade (Nanti diisi oleh tim BE)
        $totalPengeluaran = 2450000; 
        $keuangan = []; // Array kosong sementara untuk tabel

        return view('keuangan.index', compact('totalPengeluaran', 'keuangan'));
    }

    /**
     * Menampilkan form tambah pengeluaran
     */
    public function create()
    {
        // Lahan dummy untuk dropdown di form (Nanti diisi oleh tim BE)
        $lahan = []; 

        return view('keuangan.create', compact('lahan'));
    }

    /**
     * Simulasi menyimpan data (Untuk test notifikasi SweetAlert di FE)
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'lahan_id' => 'required',
            'kategori' => 'required',
            'nominal'  => 'required|numeric',
        ]);

        // Karena ini khusus FE, kita langsung redirect dengan pesan sukses 
        // agar Anda bisa melihat animasi SweetAlert-nya bekerja.
        // Nanti tim BE akan menambahkan kode: TransaksiKeuangan::create($request->all()); di sini.

        return redirect()->route('keuangan.index')
                         ->with('success', 'Data pengeluaran berhasil dicatat!');
    }

    /**
     * Simulasi hapus data
     */
    public function destroy($id)
    {
        return redirect()->route('keuangan.index')
                         ->with('success', 'Data pengeluaran berhasil dihapus!');
    }
}