<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{

    public $timestamps = false;
    
    protected $fillable = ['description', 'points'];

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }
    
}