<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotHistory extends Model
{
    use HasFactory;

    protected $table = 'chatbot_histories';

    protected $fillable = [
        'user_id',
        'lahan_id',
        'session_id',
        'question',
        'answer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_id');
    }
}
