<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Prodi; 

class AdminTISeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // 1. Cari ID Prodi untuk "Teknik Informatika"
        // Sesuaikan query ini berdasarkan kolom nama prodi Anda
        $prodiTI = Prodi::where('nama_prodi', 'Teknik Informatika')->first();

        // Pastikan prodi ditemukan sebelum membuat admin
        if ($prodiTI) {
            User::updateOrCreate(
                ['email' => 'admin.ti@campus.test'], // Ganti dengan email admin TI
                [
                    'name' => 'Admin Teknik Informatika',
                    'password' => Hash::make('password456'), // WAJIB ganti setelah login
                    'role' => 'admin',
                    'prodi_id' => $prodiTI->id, // Menggunakan ID prodi yang ditemukan
                ]
            );

            $this->command->info('Akun Admin TI berhasil dibuat.');
        } else {
            $this->command->error('Prodi "Teknik Informatika" tidak ditemukan. Seeder Admin TI dibatalkan.');
        }
    }
}