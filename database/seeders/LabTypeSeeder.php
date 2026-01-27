<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabType;

class LabTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Praktikum',
                'slug' => 'praktikum',
            ],
            [
                'name' => 'Pengujian/Sampling',
                'slug' => 'pengujian-sampling',
            ],
            [
                'name' => 'Kalibrasi',
                'slug' => 'kalibrasi',
            ],
        ];

        foreach ($types as $type) {
            LabType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
