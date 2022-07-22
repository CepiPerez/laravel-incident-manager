<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'area_id', 'module_id', 'problem_id', 'title', 'description', 'sla',
        'creator', 'assigned', 'group_id', 'status_id', 'priority', 'created_at', 'updated_at'
    ];

    public static function getIncidentList()
    {
        return Incident::with(['creator_user', 'assigned_user'])
            ->selectRaw('incidents.*, clients.description as client_desc, 
            incident_states.description as status_desc, priorities.id as pid, 
            priorities.description as pdesc')
            ->leftJoin('incident_states', 'incident_states.id', '=', 'status_id')
            ->leftJoin('clients', 'clients.id', '=', 'client_id')
            ->leftJoin('priorities', function ($join) {
                $join->on('incidents.priority', '>=', 'priorities.min');
                $join->on('incidents.priority', '<=', 'priorities.max');
            });
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function status()
    {
        return $this->belongsTo(IncidentState::class);
    }

    public function progress()
    {
        if(Auth::user()->type==1)
            return $this->hasMany(Progress::class)
                ->selectRaw('progress.*, pr.description as pr_desc')
                ->leftJoin('progress_types as pr', 'progress.progress_type_id', '=', 'pr.id')
                ->orderBy('id', 'asc');
        else
            return $this->hasMany(Progress::class)
                ->selectRaw('progress.*, pr.description as pr_desc, pr.creator_visible as visible')
                ->leftJoin('progress_types as pr', 'progress.progress_type_id', '=', 'pr.id')
                ->where('progress_type_id', '<', 100);
    }

    public function progress_short()
    {
        return $this->hasMany(Progress::class)
                ->selectRaw('incident_id, created_at, progress_type_id')
                ->where('progress_type_id', '<', 30)
                ->orderBy('created_at', 'asc');
    }

    public function creator_user()
    {
        return $this->hasOne(User::class, 'id', 'creator');
    }

    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'assigned');
    }

    public function assigned_group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function attachments()
    {
        return $this->hasMany(IncidentAttachment::class)
            ->where('progress_id', 0);
    }

    /* public function scopeSearch($query, $search)
    {
        return $query->where( function($query) use ($search) {
            return $query->where('incidents.title', 'LIKE', '%'.$search.'%')
                    ->orWhere('incidents.description', 'LIKE', '%'.$search.'%')
                   ->orWhere('incidents.id', $search);
        });
    } */

    /* public function scopeFilterByUser($query, $user)
    {
        if ($user->role_id != 1)
		{
			$perms = $user->role->permissions->pluck('id')->toArray();
			
			if (in_array(3, $perms))
				$query = $query->where( function($q) use ($user) {
					return $q->where('creator', $user->id)
						->orWhere('assigned', $user->id);
				});
			
			elseif (in_array(4, $perms))
			{
				if ($user->type==0)
					$query = $query->where('client_id', $user->client_id);
				
				else
					$query = $query->whereIn('group_id', $user->groups->pluck('id')->toArray());
			}
        }

        return $query;
    } */

    public function scopeFilter($query, $incident_filters, $request_filters)
    {
        return $incident_filters->apply($query, $request_filters);
    }

    protected function description() : Attribute
    {
        return Attribute::make (
            get: fn ($value) => html_entity_decode($value),
            set: fn ($value) => htmlentities($value)
        );
    }

    protected function title() : Attribute
    {
        return Attribute::make (
            get: fn ($value) => html_entity_decode($value),
            set: fn ($value) => htmlentities($value)
        );
    }

}