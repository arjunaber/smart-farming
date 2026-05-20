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
        Schema::table('lahan', function (Blueprint $table) {
            // Menambahkan kolom luas dengan tipe desimal (misal: 12.34 HA) diletakkan setelah kolom lokasi
            $table->decimal('luas', 8, 2)->nullable()->after('lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lahan', function (Blueprint $table) {
            $table->dropColumn('luas');
        });
    }
};
