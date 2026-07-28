<?php

namespace App\Http\Controllers\Api\Iot;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LahanController as WebLahanController;
use App\Http\Requests\Iot\LahanRequest;
use App\Models\Lahan;

class LahanController extends Controller
{
    public function show(LahanRequest $request)
    {
        $lahan = Lahan::with('komoditas')
            ->where('nama_lahan', $request->nama_lahan)
            ->first();

        if (!$lahan) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Lahan tidak ditemukan',
            ], 404);
        }

        $lokasiNama = WebLahanController::$lokasiWilayah[$lahan->lokasi] ?? $lahan->lokasi;

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail lahan ditemukan',
            'data'    => [
                'id'                  => $lahan->id,
                'nama_lahan'          => $lahan->nama_lahan,
                'lokasi_kode'         => $lahan->lokasi,
                'lokasi_nama'         => $lokasiNama,
                'status'              => $lahan->status,
                'luas'                => $lahan->luas,
                'luas_hitung'         => $lahan->hitungLuas(),
                'polygon_coordinates' => $lahan->polygon_coordinates,
                'komoditas'           => $lahan->komoditas ? [
                    'id'   => $lahan->komoditas->id,
                    'nama' => $lahan->komoditas->nama_komoditas ?? null,
                ] : null,
            ],
        ], 200);
    }
}
