<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lahan;
use App\Models\LogbookEntry;

class LahanSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@smartani.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        // Petani
        $petani = User::create([
            'name' => 'Petani Contoh',
            'email' => 'petani@smartani.com',
            'password' => bcrypt('password'),
            'role' => 'petani',
        ]);

        // Lahan for Petani
$lahan1 = Lahan::create([
            'user_id' => $petani->id,
            'nama_lahan' => 'Lahan A - Blok Padi',
            'user_id' => $petani->id,
            'nama_lahan' => 'Lahan A - Blok Padi',
            'luas' => 0.5,
            'lokasi' => 'Soreang, Kab. Bandung',
            'komoditas' => [
                ['nama' => 'Padi IR64', 'fase' => 'vegetatif', 'hari' => 45]
            ],
            'kesesuaian_score' => 92.5,
            'deskripsi' => 'Lahan utama padi sawah',
        ]);

        $lahan2 = Lahan::create([
            'user_id' => $petani->id,
            'nama_lahan' => 'Lahan B - Biofarmaka',
            'luas' => 0.25,
            'lokasi' => 'Dayeuhkolot',
            'komoditas' => [
                ['nama' => 'Jahe Merah', 'fase' => 'generatif', 'hari' => 120]
            ],
            'kesesuaian_score' => 87.0,
            'deskripsi' => 'Lahan jahe organik',
        ]);

        // Sample Logbook
        LogbookEntry::create([
            'lahan_id' => $lahan1->id,
            'tanggal' => now()->subDays(1),
            'tipe' => 'irigasi',
            'detail' => 'Penyiraman manual 2 jam',
        ]);

        LogbookEntry::create([
            'lahan_id' => $lahan1->id,
            'tanggal' => now()->subDays(10),
            'tipe' => 'pupuk',
            'detail' => 'Pemberian pupuk urea 50kg/ha',
        ]);
    }
}

