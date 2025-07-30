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
        $users = User::all();

        foreach ($users as $user) {
            // Get only THIS USER'S categories
            $categories = Category::where('user_id', $user->id)->get();

            foreach ($categories as $category) {
                // Create 10 unique subcategories for THIS category
                for ($i = 0; $i < 10; $i++) {
                    $name = fake()->words(2, true) . " " . ($i + 1); // e.g. "Electronics 1"
                    $slug = Str::slug($name . '-' . $user->id);

                    SubCategory::create([
                        'name' => $name,
                        'slug' => $slug,
                        'category_id' => $category->id,
                        'user_id' => $user->id,  // Ensure subcategory belongs to user
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
