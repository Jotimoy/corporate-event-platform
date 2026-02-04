<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organizer_id',
        'event_name',
        'event_type',
        'event_date',
        'venue_name',
        'hourly_rate',
        'status',
    ];

    public function shifts()
    {
        return $this->hasMany(EventShift::class, 'event_id');
    }
}
