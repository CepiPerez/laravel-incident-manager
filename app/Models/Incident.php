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
                ->selectRaw('progress.*, pr.description as pr_desc, u.name as user_desc, u2.name as assigned_desc')
                ->leftJoin('progress_types as pr', 'progress.progress_type_id', '=', 'pr.id')
                ->leftJoin('users as u', 'progress.user_id', '=', 'u.id')
                ->leftJoin('users as u2', 'progress.assigned_to', '=', 'u2.id')
                ->orderBy('id', 'asc');
        else
            return $this->hasMany(Progress::class)
                ->selectRaw('progress.*, pr.description as pr_desc, pr.creator_visible as visible, u.name as user_desc, u2.name as assigned_desc')
                ->leftJoin('progress_types as pr', 'progress.progress_type_id', '=', 'pr.id')
                ->leftJoin('users as u', 'progress.user_id', '=', 'u.id')
                ->leftJoin('users as u2', 'progress.assigned_to', '=', 'u2.id')
                ->where('progress_type_id', '<', 100)
                ->orderBy('id', 'asc');
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