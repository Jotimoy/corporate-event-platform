<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventShift extends Model
{
    protected $fillable = [
        'event_id',
        'shift_name',
        'shift_date',
        'start_time',
        'end_time',
        'required_employee',
        'status',
    ];

    public function applications()
    {
        return $this->hasMany(\App\Models\ShiftApplication::class, 'event_shift_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function applications()
    {
        return $this->hasMany(\App\Models\ShiftApplication::class, 'shift_id');
    }
}
