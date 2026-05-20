<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komoditas', 150);
            $table->text('panduan_rag')->nullable();
            $table->decimal('ph_min', 4, 2);
            $table->decimal('ph_max', 4, 2);
            $table->decimal('temp_min', 5, 2);
            $table->decimal('temp_max', 5, 2);
            $table->decimal('kelembapan_min', 5, 2);
            $table->decimal('kelembapan_max', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_komoditas');
    }
};