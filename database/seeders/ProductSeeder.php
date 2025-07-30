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
        $user = User::all();
        $category = Category::all();
        $subCategory = SubCategory::all();
        foreach ($user as $user) {
            foreach ($category as $category) {
                foreach ($subCategory as $subCategory) {
                    for ($i = 0; $i < 100; $i++) {
                    Product::create([
                        'name' => fake()->name(),
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
