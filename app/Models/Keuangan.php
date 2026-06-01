<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    // Menentukan nama tabel kustom Anda secara eksplisit
    protected $table = 'keuangan';

    protected $fillable = [
        'lahan_id',
        'kategori',
        'nominal',
        'tanggal',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi balik: Catatan keuangan ini milik sebuah Lahan
    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_id');
    }
}
