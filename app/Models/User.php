<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'type',
        'role_id',
        'active',
        'client_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function actives()
    {
        return User::where('active', 1)->orderBy('username')->get();
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function getAvatarAttribute()
    {
        
        if (Storage::exists('profile/'. $this->id.'.png'))
            return asset('profile/'. $this->id.'.png');
            
        if (Storage::exists('profile/'. $this->id.'.jpg'))
            return asset('profile/'. $this->id.'.jpg');

        if (Storage::exists('profile/'. $this->id.'.webp'))
            return asset('profile/'. $this->id.'.webp');
        
        return asset('profile/default.png');
    }

}
