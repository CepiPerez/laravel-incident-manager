<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class RuleCondition extends Model
{

    public $timestamps = false;
    
    protected $fillable = [
        'rule_id', 'value', 'operator', 'min', 'max', 'equal', 'helper'
    ];

}