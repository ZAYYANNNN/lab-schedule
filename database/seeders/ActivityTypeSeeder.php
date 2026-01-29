<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActivityType;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Kalibrasi',
                'description' => 'Kegiatan kalibrasi alat laboratorium',
            ],
            [
                'name' => 'Pengujian/Sampling',
                'description' => 'Kegiatan pengujian atau sampling sampel',
            ],
            [
                'name' => 'Praktikum',
                'description' => 'Kegiatan praktikum mahasiswa',
            ],
        ];

        foreach ($types as $type) {
            ActivityType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
