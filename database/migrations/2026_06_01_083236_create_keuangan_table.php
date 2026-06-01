<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nama tabel diset 'keuangan' tanpa 's'
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            // Menghubungkan langsung ke tabel 'lahan' milik Anda
            $table->foreignId('lahan_id')->constrained('lahan')->onDelete('restrict');
            $table->string('kategori'); // Pupuk, Obat Hama, Bibit, Tenaga Kerja, Lainnya
            $table->decimal('nominal', 12, 2); // Presisi aman untuk nominal uang besar
            $table->date('tanggal');
            $table->text('keterangan')->nullable(); // Detail tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
