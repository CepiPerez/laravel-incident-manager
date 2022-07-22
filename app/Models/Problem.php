<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{

    public $timestamps = false;
    
    protected $fillable = ['description', 'points', 'active'];

}