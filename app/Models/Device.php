<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'iot_devices';

    protected $fillable = [
        'lahan_id',
        'device_uid',
        'device_name',
        'device_token_hash',
        'status',
        'last_seen',
        'approved_at',
        'token_retrieved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'token_retrieved_at' => 'datetime',
        'last_seen' => 'datetime',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_id');
    }

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class, 'iot_device_id');
    }

    public function latestReading()
    {
        return $this->hasOne(SensorReading::class, 'iot_device_id')
            ->latestOfMany('recorded_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeOnline($query)
    {
        return $query->where('last_seen', '>=', now()->subMinutes(5));
    }

    public function scopeOffline($query)
    {
        return $query->where(function ($q) {
            $q->where('last_seen', '<', now()->subMinutes(5))
              ->orWhereNull('last_seen');
        });
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('lahan_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isOnline(): bool
    {
        return $this->last_seen && $this->last_seen->gte(now()->subMinutes(5));
    }
}
