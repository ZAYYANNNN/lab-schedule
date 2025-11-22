<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@campus.test'], // ganti kalau mau
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // WAJIB ganti setelah login
                'role' => 'superadmin',
                'prodi_id' => null,
            ]
        );
    }
}
