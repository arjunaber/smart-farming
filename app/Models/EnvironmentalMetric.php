<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalMetric extends Model
{
    use HasFactory;

    protected $table = 'environmental_metrics';

    protected $fillable = [
        'iot_device_id',
        'recorded_at',
        'temperature',
        'humidity',
        'ph',
        'light_intensity',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'ph' => 'decimal:2',
        'light_intensity' => 'decimal:2',
    ];

    public function iotDevice()
    {
        return $this->belongsTo(IotDevice::class, 'iot_device_id');
    }
}
