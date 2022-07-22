<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SlaRuleCondition extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'rule_id', 'condition', 'value', 'helper'
    ];

}