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
        Schema::create('plant_diseases', function (Blueprint $table) {
            $table->id();
            // nullable() benar jika user bisa scan tanpa lewat alat IoT tertentu
            $table->foreignId('iot_device_id')->nullable()->constrained('iot_devices')->onDelete('set null');
            $table->string('image_path');
            $table->string('classification_result');
            $table->decimal('accuracy', 5, 2); // Lebih baik pakai decimal untuk persentase (misal: 98.50)
            $table->text('description');
            $table->text('recommendation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_diseases');
    }
};
