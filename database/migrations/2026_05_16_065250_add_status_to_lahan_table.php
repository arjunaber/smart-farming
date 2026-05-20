<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lahan', function (Blueprint $table) {
            // Menambahkan kolom status dengan default 'Tersedia' atau 'Nonaktif'
            $table->string('status')->default('Tersedia')->after('lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('lahan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
