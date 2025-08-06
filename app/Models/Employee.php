<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    protected $fillable =
    [
    'firstname',
    'lastname',
    'cnic',
    'dob',
    'doj',
    'department_id',
    'designation_id',
    'is_active',
    'image',
    ];
}
