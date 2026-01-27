<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BorrowingStatus;

class BorrowingStatusSeeder extends Seeder
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
                'color' => 'blue'
            ],
            [
                'name' => 'Ditolak',
                'slug' => 'rejected',
                'color' => 'red'
            ],
            [
                'name' => 'Dikembalikan',
                'slug' => 'returned',
                'color' => 'green'
            ],
        ];

        foreach ($statuses as $status) {
            BorrowingStatus::updateOrCreate(['slug' => $status['slug']], $status);
        }
    }
}
