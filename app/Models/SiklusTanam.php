<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiklusTanam extends Model
{
    use HasFactory;

    protected $table = 'siklus_tanam';

    protected $fillable = [
        'lahan_id',
        'komoditas_id',
        'tanggal_mulai',
        'estimasi_panen',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'estimasi_panen' => 'date',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_id');
    }

    public function komoditas()
    {
        return $this->belongsTo(MasterKomoditas::class, 'komoditas_id');
    }

    // Siklus memiliki catatan aktivitas harian
    public function logbookEntries()
    {
        return $this->hasMany(LogbookEntry::class, 'siklus_tanam_id');
    }

    // Siklus merekam log insiden tak terduga (Hama/Bencana) untuk context LLM
    public function logKejadian()
    {
        return $this->hasMany(LogClass::class, 'siklus_tanam_id'); // Hubungan ke model LogKejadian
    }

    // Finansial per siklus tanam
    public function transaksiKeuangan()
    {
        return $this->hasMany(TransaksiKeuangan::class, 'siklus_tanam_id');
    }
}
