<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'tb_sensor';
    public $timestamps = false;

    protected $fillable = [
        'sensor_id',
        'ph',
        'humidity',
        'created_at'
    ];

    // Opsional: Casting agar tipe data lebih presisi saat dipanggil
    protected $casts = [
        'ph' => 'float',
        'humidity' => 'integer',
        'created_at' => 'datetime',
    ];
}