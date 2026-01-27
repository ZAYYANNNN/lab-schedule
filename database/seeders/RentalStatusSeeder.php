<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentalStatus;

class RentalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Menunggu',
                'slug' => 'pending',
                'color' => 'amber'
            ],
            [
                'name' => 'Disetujui',
                'slug' => 'approved',
                'color' => 'indigo'
            ],
            [
                'name' => 'Ditolak',
                'slug' => 'rejected',
                'color' => 'red'
            ],
            [
                'name' => 'Selesai',
                'slug' => 'completed',
                'color' => 'green'
            ],
        ];

        foreach ($statuses as $status) {
            RentalStatus::create($status);
        }
    }
}
