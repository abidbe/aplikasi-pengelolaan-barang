<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(100)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'username' => 'admin',
            'role' => 'admin',
            'is_locked' => false,
            'password' => bcrypt('test'),
            'remember_token' => null,
            'email_verified_at' => now(),

        ]);
    }
}
