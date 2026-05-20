<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SopDocument extends Model
{
    use HasFactory;

    protected $table = 'sop_documents';

    protected $fillable = [
        'komoditas_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'status',
    ];

    public function komoditas()
    {
        return $this->belongsTo(MasterKomoditas::class, 'komoditas_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
