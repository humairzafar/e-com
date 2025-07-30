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
        $users = [
            [
                'email' => 'ali@velzon.com',
                'name' => 'Ali Hassan',
                'password' => 'vision',
            ],

            [
                'name' => 'Admin User',
                'email' => 'admin@velzon.com',
                'password' => Hash::make('password'),
            ],

            [
                'email' => 'm87683941@gmail.com',
                'name' => 'web developer',
                'password' => 'admin',
            ],

            [
                'email' => 'ashfaq@velzon.com',
                'name' => 'Ashfaq',
                'password' => 'syntix',
            ],
            [
                'email' => 'haider@velzon.com',
                'name' => 'haider',
                'password' => 'password123',
            ],

        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], // Match by email
                $user
            );
        }
    }
}
