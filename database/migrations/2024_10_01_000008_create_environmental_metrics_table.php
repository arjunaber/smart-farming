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
        Schema::create('environmental_metrics', function (Blueprint $table) {
            $table->id();
            // Tambahkan relasi ke device
            $table->foreignId('iot_device_id')->constrained('iot_devices')->onDelete('cascade');
            $table->float('temperature');
            $table->float('humidity');
            $table->float('ph');
            $table->float('light_intensity');
            // Gunakan timestamps standar Laravel atau recorded_at
            $table->timestamp('recorded_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environmental_metrics');
    }
};