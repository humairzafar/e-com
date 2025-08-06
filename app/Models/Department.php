<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = true;
    protected $fillable = ['name',
    'is_active',
    'is_head_office_department',
    'user_id',
];
}
