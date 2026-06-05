<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lahan_id')->nullable()->constrained('lahan')->nullOnDelete();
            $table->string('device_uid', 64)->unique();
            $table->string('device_name', 100)->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('device_token_hash', 64)->nullable()->index();
            $table->string('status', 20)->default('active');
            $table->timestamp('last_seen')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('token_retrieved_at')->nullable();
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('iot_devices');
    }
};