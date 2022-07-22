<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityRule extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['description', 'points', 'active'];

    public function conditions()
    {
        return $this->hasMany(PriorityRuleCondition::class, 'rule_id');
    }
    

}
