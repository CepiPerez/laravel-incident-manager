<?php

namespace App\Filters;

class UserPermissionsFilter
{
    function __invoke($query, $user)
    {
        if ($user->role_id != 1)
		{
			$perms = $user->role->permissions->pluck('id')->toArray();
			
			if (in_array(3, $perms))
			{
				$query = $query->where( function($q) use ($user) {
					return $q->where('creator', $user->id)
						->orWhere('assigned', $user->id);
				});
			}			
			elseif (in_array(4, $perms))
			{
				if ($user->type==0)
					$query = $query->where('client_id', $user->client_id);
				
				else
					$query = $query->whereIn('group_id', $user->groups->pluck('id')->toArray());
			}
        }

        return $query;
    }
}