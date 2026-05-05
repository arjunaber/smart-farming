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
        'deskripsi',
    ];

    protected $casts = [
        'komoditas' => 'array',
        'luas' => 'decimal:2',
        'kesesuaian_score' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logbookEntries()
    {
        return $this->hasMany(LogbookEntry::class);
    }

    public function getKomoditasUtamaAttribute()
    {
        return $this->komoditas[0]['nama'] ?? 'Belum ditentukan';
    }
}

