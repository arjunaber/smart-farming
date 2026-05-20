<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_sensor', function (Blueprint $table) {
            $table->id();
            $table->string('sensor_id');
            $table->float('ph'); // pH
            $table->integer('humidity');  // Tanah
            $table->timestamp('created_at')->useCurrent(); // Penting agar Express bisa pakai NOW()
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_sensor');
    }
};