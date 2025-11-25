<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        // Masukkan data Prodi yang diperlukan
        Prodi::updateOrCreate(
            ['nama_prodi' => 'Teknik Informatika'],
            ['kode' => 'TI']
        );

        Prodi::updateOrCreate(
            ['nama_prodi' => 'Teknik Sipil'],
            ['kode' => 'TS']
        );
        
        $this->command->info('Data Prodi berhasil dimasukkan.');
    }
}