<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftApplication extends Model
{
    protected $fillable = [
        'event_shift_id',
        'user_id',
        'status',
    ];

    public function shift()
    {
        return $this->belongsTo(EventShift::class, 'event_shift_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
