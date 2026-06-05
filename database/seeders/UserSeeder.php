<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Flustra Admin',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
