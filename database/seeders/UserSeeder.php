<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'first_name' => "User{$i}",
                'last_name' => "Test{$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'phone' => '07900000' . $i,
                'date_of_birth' => now()->subYears(20 + $i)->format('Y-m-d'),
                'gender' => $i % 2 === 0 ? 'male' : 'female',
                'country_id' => 1,
                'role' => 'user',
                'about_me' => "I'm User{$i}, nice to meet you.",
                'image_path' => null,
            ]);
        }
    }
}
