<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Get categories for this specific user
            $categories = Category::where('user_id', $user->id)->get();

            foreach ($categories as $category) {
                // Get subcategories for this specific user and category
                $subCategories = SubCategory::where('user_id', $user->id)
                                        ->where('category_id', $category->id)
                                        ->get();

                foreach ($subCategories as $subCategory) {
                    // Create exactly 10 products for each subcategory
                    for ($i = 0; $i < 10; $i++) {
                        Product::create([
                            'name' => fake()->words(3, true),
                            'sku' => fake()->unique()->numberBetween(100000, 999999),
                            'price' => fake()->randomFloat(2, 10, 1000),
                            'description' => fake()->paragraph(),
                            'category_id' => $category->id,
                            'sub_category_id' => $subCategory->id,
                            'user_id' => $user->id,
                            'is_active' => 1,
                        ]);
                    }
                }
            }
        }
    }
}
