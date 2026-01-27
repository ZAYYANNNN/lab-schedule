<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabStatus;

class LabStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Tersedia',
                'slug' => 'tersedia',
                'color' => 'green'
            ],
            [
                'name' => 'Digunakan',
                'slug' => 'digunakan',
                'color' => 'yellow'
            ],
            [
                'name' => 'Maintenance',
                'slug' => 'maintenance',
                'color' => 'red'
            ],
        ];

        foreach ($statuses as $status) {
            LabStatus::create($status);
        }
    }
}
