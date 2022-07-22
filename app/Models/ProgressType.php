<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressType extends Model
{

    public $timestamps = false;
    
    protected $fillable = ['id', 'description', 'client_visible', 'send_email'];

}