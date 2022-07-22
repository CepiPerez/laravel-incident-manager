<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignationCondition extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'rule_id', 'condition', 'value', 'helper'
    ];
}
