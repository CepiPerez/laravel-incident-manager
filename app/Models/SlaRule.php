<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaRule extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['description', 'sla', 'active'];

    public function conditions()
    {
        return $this->hasMany(SlaRuleCondition::class, 'rule_id');
    }

}
