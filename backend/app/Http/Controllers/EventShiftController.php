<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventShift;
use Illuminate\Http\Request;

class EventShiftController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'shift_name' => 'required|string',
            'shift_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'required_employee' => 'required|integer|min:1',
        ]);

        $shift = $event->shifts()->create([
            'shift_name' => $validated['shift_name'],
            'shift_date' => $validated['shift_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'required_employee' => $validated['required_employee'],
            'status' => 'open',
        ]);

        return response()->json([
            'message' => 'Event shift created successfully',
            'shift' => $shift
        ], 201);
    }
}
