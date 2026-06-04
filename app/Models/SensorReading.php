<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'iot_device_id',
        'ph',
        'humidity',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'ph' => 'decimal:2',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'iot_device_id');
    }
}
