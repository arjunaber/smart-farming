<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi One-to-One ke profil Petani
    public function petani()
    {
        return $this->hasOne(Petani::class, 'user_id');
    }

    // Dokumen SOP yang diunggah oleh user (Admin/Penyuluh)
    public function sopDocuments()
    {
        return $this->hasMany(SopDocument::class, 'uploaded_by');
    }

    // Riwayat Chatbot milik user
    public function chatbotHistories()
    {
        return $this->hasMany(ChatbotHistory::class, 'user_id');
    }
}