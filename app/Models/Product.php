<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'quantity',
        'unit_cost_price',
        'price',
        'category_id',
        'sub_category_id',
        'is_active',
        'image_url',
        'description',
        'user_id'
    ];



    protected $casts = [
        'is_active' => 'boolean',
        'unit_cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];
    // Relationship to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship to SubCategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
        // If your foreign key is different from the default (sub_category_id vs subCategory_id)
    }



    public function getImageUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('images/default-product.png');
    }













    // Add this method for image URL access
    // public function getImageUrlAttribute()
    // {
    // }
}
