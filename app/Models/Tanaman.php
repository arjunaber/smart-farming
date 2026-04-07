<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanaman extends Model
{
    use HasFactory;

    protected $table = 'tanaman';

    protected $fillable = [
        'nama_tanaman',
        'tanggal_tanam',
        'durasi_min',
        'durasi_max',
        'fase_saat_ini',
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
    ];
}
