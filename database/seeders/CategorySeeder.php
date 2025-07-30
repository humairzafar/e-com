<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::all();

        //make 1000 categories for each user with faker library
        foreach ($user as $user) {
            for ($i = 0; $i < 1000; $i++) {
                Category::create([
                    'name' => fake()->name(),
                    'slug' => Str::slug(fake()->name()),
                    'user_id' => $user->id,
                    'is_active' => 1,
                ]);
            }
        }


    }
}
