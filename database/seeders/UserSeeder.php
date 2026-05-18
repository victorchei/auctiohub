<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'AuctioHub Admin',
            'email' => 'admin@auctiohub.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Test User {$i}",
                'email' => "user{$i}@auctiohub.test",
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }

        User::factory(15)->create();
    }
}
