<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignation extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['description', 'group_id', 'user_id', 'active'];

    public function conditions()
    {
        return $this->hasMany(AssignationCondition::class, 'rule_id');
    }

}
