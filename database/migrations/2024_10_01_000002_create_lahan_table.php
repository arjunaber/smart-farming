<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_lahan');
            $table->decimal('luas', 8, 2)->default(0.25); // hektar
            $table->string('lokasi');
            $table->json('komoditas'); // [{"nama":"Padi IR64", "fase":"vegetatif"}]
            $table->decimal('kesesuaian_score', 5, 2)->default(0); // %
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lahan');
    }
};

