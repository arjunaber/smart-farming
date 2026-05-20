<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    use HasFactory;

    protected $table = 'lahan';

    protected $fillable = [
        'petani_id',
        'nama_lahan',
        'komoditas_id',
        'polygon_coordinates',
        'lokasi',
        'status',
        'luas',
    ];

    protected $casts = [
        'polygon_coordinates' => 'array',
    ];


    // Pemilik lahan
    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id');
    }

    // Lahan memiliki banyak riwayat/antrean siklus tanam
    public function siklusTanam()
    {
        return $this->hasMany(SiklusTanam::class, 'lahan_id');
    }

    // Perangkat IoT yang terpasang di lahan ini
    public function iotDevices()
    {
        return $this->hasMany(IotDevice::class, 'lahan_id');
    }

    // Riwayat konsultasi chatbot terkait lahan ini
    public function chatbotHistories()
    {
        return $this->hasMany(ChatbotHistory::class, 'lahan_id');
    }

    public function komoditas()
    {
        return $this->belongsTo(MasterKomoditas::class, 'komoditas_id');
    }

    public function hitungLuas()
    {
        // 1. Pastikan data tidak kosong
        if (empty($this->polygon_coordinates)) {
            return 0;
        }

        // 2. Paksa konversi ke array jika Laravel masih menganggapnya string
        $coords = $this->polygon_coordinates;
        if (is_string($coords)) {
            $coords = json_decode($coords, true);
        }

        // 3. Validasi final apakah $coords benar-benar array dan bisa dihitung
        if (!is_array($coords) || count($coords) <= 2) {
            return 0;
        }

        $area = 0;
        $numCoords = count($coords); // Simpan jumlah count agar lebih efisien

        // Rumus Spherical Area
        for ($i = 0; $i < $numCoords; $i++) {
            $j = ($i + 1) % $numCoords;

            // Pastikan key 'lat' dan 'lng' ada agar tidak error "Undefined Index"
            if (isset($coords[$i]['lat'], $coords[$i]['lng'], $coords[$j]['lat'], $coords[$j]['lng'])) {
                $area += deg2rad($coords[$j]['lng'] - $coords[$i]['lng']) *
                    (2 + sin(deg2rad($coords[$i]['lat'])) + sin(deg2rad($coords[$j]['lat'])));
            }
        }

        $area = abs($area * 6378137 * 6378137 / 2);

        return round($area, 2);
    }
}
