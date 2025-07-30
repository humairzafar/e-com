<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::all();

        //make 100 subcategories for each category
        $category = Category::all();
        foreach ($user as $user) {
            foreach ($category as $category) {
                for ($i = 0; $i < 100; $i++) {
                    $name = fake()->unique()->name();
                    $slug = Str::slug($name . $category->id . $i);
                    SubCategory::create([
                        'name' => $name,
                        'slug' => $slug,
                        'category_id' => $category->id,
                        'user_id' => $user->id,
                        'is_active' => fake()->boolean(),
                    ]);
                }
            }
        }
    }
}
