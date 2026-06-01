<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RagDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'original_filename',
        'stored_filename',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'status',
        'error_message',
        'collection_name',
        'chunk_count',
        'token_count',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size'   => 'integer',
        'chunk_count' => 'integer',
        'token_count' => 'integer',
    ];

    // ------------------------------------------------------------------
    // Status constants
    // ------------------------------------------------------------------
    const STATUS_UPLOADED   = 'uploaded';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PROCESSED  = 'processed';
    const STATUS_FAILED     = 'failed';

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ------------------------------------------------------------------
    // Scopes
    // ------------------------------------------------------------------
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCollection($query, string $collection)
    {
        return $query->where('collection_name', $collection);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', self::STATUS_PROCESSED);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_UPLOADED, self::STATUS_PROCESSING]);
    }

    // ------------------------------------------------------------------
    // Accessors
    // ------------------------------------------------------------------
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024)       return "{$bytes} B";
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }

    public function getDownloadUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_UPLOADED   => ['label' => 'Diunggah',   'color' => 'blue'],
            self::STATUS_PROCESSING => ['label' => 'Diproses',   'color' => 'yellow'],
            self::STATUS_PROCESSED  => ['label' => 'Siap',       'color' => 'green'],
            self::STATUS_FAILED     => ['label' => 'Gagal',      'color' => 'red'],
            default                 => ['label' => 'Tidak Diketahui', 'color' => 'gray'],
        };
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------
    public function markAsProcessing(): bool
    {
        return $this->update(['status' => self::STATUS_PROCESSING, 'error_message' => null]);
    }

    public function markAsProcessed(int $chunkCount = null, int $tokenCount = null): bool
    {
        return $this->update([
            'status'      => self::STATUS_PROCESSED,
            'chunk_count' => $chunkCount,
            'token_count' => $tokenCount,
            'error_message' => null,
        ]);
    }

    public function markAsFailed(string $errorMessage): bool
    {
        return $this->update([
            'status'        => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_UPLOADED, self::STATUS_PROCESSING]);
    }

    public function isProcessed(): bool
    {
        return $this->status === self::STATUS_PROCESSED;
    }
}
