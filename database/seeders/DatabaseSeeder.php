<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 Akun Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@muliadis.com',
            'password' => Hash::make('password'), // password default
            'role' => 'admin',
        ]);

        // Buat 1 Akun Pimpinan (Opsional)
        User::create([
            'name' => 'Bapak Pimpinan',
            'username' => 'pimpinan',
            'email' => 'bos@muliadis.com',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);

        // Buat 1 Akun Pengguna Biasa (Opsional)
        User::create([
            'name' => 'Staff Gudang',
            'username' => 'staff',
            'email' => 'staff@muliadis.com',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
        ]);
    }
}