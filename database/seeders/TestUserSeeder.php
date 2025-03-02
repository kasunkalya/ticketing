<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'], // Prevent duplicate entries
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'), // Change as needed
            ]
        );
    }
}
