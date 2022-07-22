<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['description', 'min', 'max'];
    
}