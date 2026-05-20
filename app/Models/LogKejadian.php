<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKejadian extends Model
{
    use HasFactory;

    protected $table = 'log_kejadian';

    protected $fillable = [
        'siklus_tanam_id',
        'iot_device_id',
        'tanggal_kejadian',
        'kategori_kejadian',
        'deskripsi',
        'tindakan_penanganan',
        'tingkat_kerusakan',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'datetime',
    ];

    public function siklusTanam()
    {
        return $this->belongsTo(SiklusTanam::class, 'siklus_tanam_id');
    }

    // Perangkat IoT opsional yang barangkali otomatis mendeteksi/mengirim log insiden
    public function iotDevice()
    {
        return $this->belongsTo(IotDevice::class, 'iot_device_id');
    }
}
