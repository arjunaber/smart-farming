<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logbook_entries', function (Blueprint $table) {
            $table->id();
$table->foreignId('lahan_id')->constrained('lahan')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('tipe', ['tanam', 'pupuk', 'irigasi', 'panen', 'hama', 'lainnya']);
            $table->text('detail');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logbook_entries');
    }
};

