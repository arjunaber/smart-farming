<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    use HasFactory;

    protected $table = 'petani';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik',
        'no_hp',
        'alamat',
        'kelompok_tani',
    ];

    // Profil petani terhubung ke akun User login
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Petani memiliki banyak lahan
    public function lahan()
    {
        return $this->hasMany(Lahan::class, 'petani_id');
    }
}