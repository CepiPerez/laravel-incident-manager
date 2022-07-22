<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class IncidentAttachment extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['incident_id', 'progress_id', 'attachment'];

}