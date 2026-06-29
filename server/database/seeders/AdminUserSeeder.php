<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nihonaccess.test'],
            [
                'name' => 'Admin NihonAccess',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'sensei@nihonaccess.test'],
            [
                'name' => 'Sensei Tanaka',
                'username' => 'sensei',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
