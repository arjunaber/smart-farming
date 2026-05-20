<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Petani;
use App\Models\MasterKomoditas;
use App\Models\Lahan;
use App\Models\SiklusTanam;
use App\Models\LogbookEntry;
use Illuminate\Support\Facades\Hash;

class LahanSeeder extends Seeder
{
    public function run()
    {
        // 1. Super Admin (Akun User)
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@smartani.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Petani (Akun User)
        $userPetani = User::create([
            'name' => 'Petani Contoh',
            'email' => 'petani@smartani.com',
            'password' => Hash::make('password'),
            'role' => 'petani',
        ]);

        // 3. Buat Profil Petani (Terhubung ke user_id)
        $petani = Petani::create([
            'user_id' => $userPetani->id,
            'nama_lengkap' => 'Samuel Arjuna Queen Bernard',
            'nik' => '3204112233440001',
            'no_hp' => '081234567890',
            'alamat' => 'Soreang, Kab. Bandung',
            'kelompok_tani' => 'Maju Bersama Mekaar',
        ]);

        // 4. Master Komoditas (Wajib dibuat sebelum Lahan)
        $padi = MasterKomoditas::create([
            'nama_komoditas' => 'Padi IR64',
            'panduan_rag' => 'Panduan penanaman padi sawah dengan irigasi intermiten.',
            'ph_min' => 5.5,
            'ph_max' => 7.0,
            'temp_min' => 22.0,
            'temp_max' => 32.0,
            'kelembapan_min' => 60.0,
            'kelembapan_max' => 85.0,
        ]);

        $jahe = MasterKomoditas::create([
            'nama_komoditas' => 'Jahe Merah',
            'panduan_rag' => 'Panduan budidaya jahe organik di media bedengan tanah gembur.',
            'ph_min' => 6.0,
            'ph_max' => 7.5,
            'temp_min' => 20.0,
            'temp_max' => 30.0,
            'kelembapan_min' => 70.0,
            'kelembapan_max' => 90.0,
        ]);

        // 5. Lahan untuk Petani (Sekarang menyertakan komoditas_id)
        $lahan1 = Lahan::create([
            'petani_id' => $petani->id,
            'nama_lahan' => 'Lahan A - Blok Padi',
            'komoditas_id' => $padi->id, // Relasi ke MasterKomoditas
            'polygon_coordinates' => json_encode([
                ["lat" => -7.0234, "lng" => 107.5123],
                ["lat" => -7.0235, "lng" => 107.5129],
                ["lat" => -7.0240, "lng" => 107.5128],
                ["lat" => -7.0239, "lng" => 107.5122]
            ]),
            'lokasi' => 'Soreang, Kab. Bandung',
        ]);

        $lahan2 = Lahan::create([
            'petani_id' => $petani->id,
            'nama_lahan' => 'Lahan B - Biofarmaka',
            'komoditas_id' => $jahe->id, // Relasi ke MasterKomoditas
            'polygon_coordinates' => json_encode([
                ["lat" => -6.9744, "lng" => 107.6311],
                ["lat" => -6.9745, "lng" => 107.6315],
                ["lat" => -6.9749, "lng" => 107.6314],
                ["lat" => -6.9748, "lng" => 107.6310]
            ]),
            'lokasi' => 'Dayeuhkolot, Bandung',
        ]);

        // 6. Buat Siklus Tanam
        $siklus1 = SiklusTanam::create([
            'lahan_id' => $lahan1->id,
            'komoditas_id' => $padi->id,
            'tanggal_mulai' => now()->subDays(45),
            'estimasi_panen' => now()->addDays(75),
            'status' => 'aktif',
        ]);

        $siklus2 = SiklusTanam::create([
            'lahan_id' => $lahan2->id,
            'komoditas_id' => $jahe->id,
            'tanggal_mulai' => now()->subDays(120),
            'estimasi_panen' => now()->addDays(120),
            'status' => 'aktif',
        ]);

        // 7. Logbook Entries
        LogbookEntry::create([
            'siklus_tanam_id' => $siklus1->id,
            'activity_date' => now()->subDays(1),
            'jenis_kegiatan' => 'Pengairan',
            'title' => 'Irigasi Rutin',
            'description' => 'Penyiraman otomatis menggunakan pompa pintar selama 2 jam.',
            'kuantitas' => 2000.00,
            'satuan' => 'liter',
        ]);

        LogbookEntry::create([
            'siklus_tanam_id' => $siklus1->id,
            'activity_date' => now()->subDays(10),
            'jenis_kegiatan' => 'Pemupukan',
            'title' => 'Pemberian Pupuk Urea',
            'description' => 'Pemberian pupuk susulan fase vegetatif utama.',
            'kuantitas' => 50.00,
            'satuan' => 'kg',
        ]);
    }
}
