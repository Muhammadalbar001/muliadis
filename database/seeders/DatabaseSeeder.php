<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'super@mulia.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // 2. Pimpinan
        User::create([
            'name' => 'Bapak Pimpinan',
            'username' => 'pimpinan',
            'email' => 'boss@mulia.com',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);

        // 3. Supervisor
        User::create([
            'name' => 'Area Supervisor',
            'username' => 'supervisor',
            'email' => 'spv@mulia.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        // 4. Admin (Dulu Staff)
        User::create([
            'name' => 'Staff Admin',
            'username' => 'admin',
            'email' => 'admin@mulia.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}