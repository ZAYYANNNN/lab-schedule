<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            'Teknik Informatika',
            'Teknik Elektro',
            'Teknik Mesin',
            'Teknik Sipil',
            'Arsitektur',
        ];

        foreach ($prodis as $name) {
            Prodi::firstOrCreate(['name' => $name]);
        }
        
        $this->command->info('Data Prodi berhasil dimasukkan.');
    }
}