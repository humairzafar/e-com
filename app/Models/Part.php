<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = ['name', 'slug', 'is_active','location_id'];
    public function location()
{
    return $this->belongsTo(Location::class);
}
}
