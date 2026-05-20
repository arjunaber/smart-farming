<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_kejadian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_tanam_id')->constrained('siklus_tanam')->cascadeOnDelete();
            $table->foreignId('iot_device_id')->nullable()->constrained('iot_devices')->nullOnDelete();
            $table->timestamp('tanggal_kejadian');
            $table->string('kategori_kejadian'); // Serangan Hama, Cuaca Ekstrem, Pencurian, dll
            $table->text('deskripsi');
            $table->text('tindakan_penanganan')->nullable();
            $table->string('tingkat_kerusakan')->nullable(); // Ringan, Sedang, Parah, Gagal Panen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_kejadian');
    }
};
