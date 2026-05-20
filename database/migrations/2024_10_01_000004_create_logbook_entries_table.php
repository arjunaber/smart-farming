<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_tanam_id')->constrained('siklus_tanam')->cascadeOnDelete();
            $table->date('activity_date');
            $table->string('jenis_kegiatan'); // Pembenihan, Pemupukan, Pengairan, Panen, dll
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->decimal('kuantitas', 10, 2)->nullable();
            $table->string('satuan', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_entries');
    }
};
