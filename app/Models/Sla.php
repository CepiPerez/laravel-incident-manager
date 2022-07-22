<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sla extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = 'sla';

    protected $fillable = ['sla_default', 'sla_notify'];

    public function rules()
    {
        return $this->hasMany(SlaRule::class);
    }


}
