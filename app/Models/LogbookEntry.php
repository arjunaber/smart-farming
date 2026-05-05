<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogbookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'lahan_id',
        'tanggal',
        'tipe',
        'detail',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }

    public function getTipeIconAttribute()
    {
        return match($this->tipe) {
            'tanam' => '🌱',
            'pupuk' => '🧪',
            'irigasi' => '💧',
            'panen' => '🌾',
            'hama' => '🐛',
            default => '📝',
        };
    }
}

