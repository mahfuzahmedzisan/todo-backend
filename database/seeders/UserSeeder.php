<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@dev.com',
            'password' => 'admin@dev.com',
            'is_admin' => User::ADMIN,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@dev.com',
            'password' => 'user@dev.com',
            'is_admin' => User::NOT_ADMIN,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'User 2',
            'email' => 'user2@dev.com',
            'password' => 'user2@dev.com',
            'is_admin' => User::NOT_ADMIN
        ]);
    }
}
