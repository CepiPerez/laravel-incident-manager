<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public $timestamps = false;
    
    protected $fillable = ['description', 'type'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
}