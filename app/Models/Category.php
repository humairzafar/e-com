<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'is_active','user_id'];


    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}



