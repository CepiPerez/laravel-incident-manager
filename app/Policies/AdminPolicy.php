<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function isAdmin($user)
    {
        return $user->role_id==1;
    }

    public function crearInc($user)
    {
        if ($user->role_id==1) return true;
        
        $perm = $user->role->permissions?->pluck('id')->toArray();
        return ( in_array(1, $perm) );
    }

    public function cargaMasiva($user)
    {
        if ($user->role_id==1) return true;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(5, $perm);
    }

    public function tableroControl($user)
    {
        if ($user->role_id==1) return true;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(6, $perm);
    }

    public function informes($user)
    {
        if ($user->role_id==1) return true;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(7, $perm);
    }

    public function adminPanel($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return ( in_array(8, $perm) || in_array(9, $perm) || in_array(10, $perm) 
            || in_array(11, $perm) || in_array(12, $perm) || in_array(13, $perm)
            || in_array(14, $perm) || in_array(15, $perm)  || in_array(16, $perm));
    }

    public function adminUsuarios($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return ( in_array(8, $perm) || in_array(9, $perm) );
    }

    public function adminRoles($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(10, $perm);
    }

    public function adminClientes($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(11, $perm);
    }

    public function adminAreas($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(12, $perm);
    }

    public function adminModulos($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(13, $perm);
    }

    public function adminTipoIncidente($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(14, $perm);
    }

    public function adminTipoServicio($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(15, $perm);
    }

    public function adminTipoAvance($user)
    {
        if ($user->role_id==1) return true;
        if ($user->tipo==0) return false;

        $perm = $user->role->permissions?->pluck('id')->toArray();
        return in_array(16, $perm);
    }

    public function verIncidente($user, $inc)
    {

        if ($user->role_id==1) return true;

        $perm = $user->role->permissions?->pluck('id')->toArray();

        if (in_array(2, $perm))
            return true;

        if (in_array(3, $perm) && ($inc->creator==$user->id || $inc->assigned==$user->id) )
            return true;

        if (in_array(4, $perm) && ($inc->client->id==$user->client_id) 
            || in_array($inc->group_id, $user->groups->pluck('id')->toArray())
            || $inc->assigned==$user->id )
            return true;

        return false;

    }
}
