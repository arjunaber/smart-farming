<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotDevice extends Model
{
    use HasFactory;

    protected $table = 'iot_devices';

    protected $fillable = [
        'lahan_id',
        'device_name',
        'device_type',
        'serial_number',
        'status',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_id');
    }

    // IoT mencatat metrik lingkungan secara berkala
    public function environmentalMetrics()
    {
        return $this->hasMany(EnvironmentalMetric::class, 'iot_device_id');
    }

    // Perangkat IoT memicu log kejadian jika ada kerusakan otomatis
    public function logKejadian()
    {
        return $this->hasMany(LogKejadian::class, 'iot_device_id');
    }
}