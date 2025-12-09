<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    User::factory()->create([
        'name' => 'Administrator',
        'username' => 'admin', // Username untuk login
        'email' => 'admin@muliadis.com',
        'password' => Hash::make('password'), // Password default
    ]);
}
}