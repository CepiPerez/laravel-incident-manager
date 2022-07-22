<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{

    protected $fillable = ['incident_id', 'progress_type_id', 'user_id', 'description', 'created_at',
        'assigned_to', 'assigned_group_to', 'prev_status', 'prev_assigned', 'prev_assigned_group'];


    protected function description() : Attribute
    {
        return Attribute::make (
            get: fn ($value) => html_entity_decode($value),
            set: fn ($value) => htmlentities($value)
        );
    }

        
    protected function attachments() : Attribute
    {
        return Attribute::make (
            get: fn ($value) => IncidentAttachment::where('progress_id', $this->id)
                                ->where('incident_id', $this->incident_id)->get()
        );

    }


    
}