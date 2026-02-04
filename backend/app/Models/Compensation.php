<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compensation extends Model
{
    protected $fillable = [
        'user_id',
        'event_shift_id',
        'hours_worked',
        'hourly_rate',
        'total_amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(EventShift::class, 'event_shift_id');
    }
}
