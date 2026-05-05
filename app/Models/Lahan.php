<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    use HasFactory;

    protected $table = 'lahan';

    protected $fillable = [
        'user_id',
        'nama_lahan',
        'luas',
        'lokasi',
        'komoditas',
        'kesesuaian_score',
        'deskripsi'
    ];

    protected $casts = [
        'komoditas' => 'array',
        'luas' => 'float',
        'kesesuaian_score' => 'integer',
    ];

    // Helper untuk mengambil nama komoditas utama tanpa error
    public function getNamaKomoditasAttribute()
    {
        return $this->komoditas[0]['nama'] ?? 'Tanpa Komoditas';
    }

    public function getFaseTanamanAttribute()
    {
        return $this->komoditas[0]['fase'] ?? 'N/A';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logbookEntries()
    {
        return $this->hasMany(LogbookEntry::class);
    }
}
