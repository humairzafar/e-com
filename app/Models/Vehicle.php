<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VehiclesCategory;
use App\Models\Town;

class Vehicle extends Model
{
     protected $fillable = ['vehicle_id', 'category_id', 'town_id','condition','status'];

     public function category()
     {
         return $this->belongsTo(VehiclesCategory::class);
     }
     public function town()
     {
        return $this->belongsTo(Town::class);
     }
}
