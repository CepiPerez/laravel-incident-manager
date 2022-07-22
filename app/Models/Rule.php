<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{

    public $timestamps = false;
    
    protected $fillable = [
        'description', 'points', 'active'
    ];

    public function conditions()
    {
        return $this->hasMany(RuleConditions::class, 'regla');
    }
    
}