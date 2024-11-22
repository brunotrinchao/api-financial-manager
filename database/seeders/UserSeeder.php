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
        User::create([
            'name' => 'Admin User',
            'email' => 'brunotrinchao@gmail.com',
            'password' => Hash::make('12345678'),
            'access_level' => 'admin',
            'avatar' => 'https://example.com/images/admin.jpg',
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'access_level' => 'user',
            'avatar' => 'https://example.com/images/user.jpg',
        ]);
    }
}
