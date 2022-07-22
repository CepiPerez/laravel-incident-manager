<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentState extends Model
{

    public $timestamps = false;
    
    protected $fillable = ['id', 'description'];

}