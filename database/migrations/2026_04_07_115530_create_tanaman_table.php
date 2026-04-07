<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->date('tanggal_tanam');
            $table->integer('durasi_hari')->nullable();
            $table->string('fase_saat_ini')->default('Penanaman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanaman');
    }
};