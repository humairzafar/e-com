<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin =
            [
                'email' => 'admin12@gmail.com',
                'name' => 'Admin',
                'password' => 'admin',
            ];


        $users = [
            'name' => 'User',
            'email' => 'use12r@gmail.com',
            'password' => 'user',
        ];

        User::create([
            'name' => $admin['name'],
            'email' => $admin['email'],
            'password' => Hash::make($admin['password']),
            'is_active' => true,
        ])->assignRole('Admin');

        User::create([
            'name' => $users['name'],
            'email' => $users['email'],
            'password' => Hash::make($users['password']),
            'is_active' => true,
        ])->assignRole('User');
    }
}
