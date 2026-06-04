<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('petani')->cascadeOnDelete();
            $table->string('nama_lahan', 150);
            $table->foreignId('komoditas_id')->constrained('master_komoditas');
            $table->json('polygon_coordinates')->nullable();
            $table->string('lokasi', 255);
            $table->decimal('luas', 8, 2)->nullable();
            $table->string('status')->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lahan');
    }
};
