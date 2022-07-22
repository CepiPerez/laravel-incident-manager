<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['description', 'service_type_id', 'active'];

    public static function actives()
    {
        return Client::where('active', 1)->orderBy('description')->get();
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function service()
    {
        return $this->belongsTo(ServiceType::class);
    }


}