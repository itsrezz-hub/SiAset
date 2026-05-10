<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Superadmin
        User::create([
            'name' => 'Superadmin Inventaris',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);

        // 2. Akun Admin
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 3. Akun User (Opsional)
        User::create([
            'name' => 'User Sampel',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}