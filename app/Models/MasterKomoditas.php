<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKomoditas extends Model
{
    use HasFactory;

    protected $table = 'master_komoditas';

    protected $fillable = [
        'nama_komoditas',
        'panduan_rag',
        'ph_min',
        'ph_max',
        'temp_min',
        'temp_max',
        'kelembapan_min',
        'kelembapan_max',
    ];

    // Komoditas digunakan di banyak siklus tanam
    public function siklusTanam()
    {
        return $this->hasMany(SiklusTanam::class, 'komoditas_id');
    }

    // Komoditas memiliki dokumen SOP terkait
    public function sopDocuments()
    {
        return $this->hasMany(SopDocument::class, 'komoditas_id');
    }
}