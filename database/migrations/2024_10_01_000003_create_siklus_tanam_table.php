<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siklus_tanam', function (Blueprint $table) {
            $table->id();
            // Pastikan kolom lahan_id ini ada dan merujuk ke tabel lahan
            $table->foreignId('lahan_id')->constrained('lahan')->cascadeOnDelete();
            $table->foreignId('komoditas_id')->constrained('master_komoditas')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('estimasi_panen')->nullable();
            $table->string('status')->nullable(); // aktif, selesai, gagal
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siklus_tanam');
    }
};
