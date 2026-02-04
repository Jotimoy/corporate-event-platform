<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string',
            'event_type' => 'required|string',
            'venue_name' => 'required|string',
            'event_date' => 'required|date',
            'hourly_rate' => 'required|numeric',
        ]);

        $event = Event::create([
            'organizer_id' => Auth::id(),
            'event_name' => $validated['event_name'],
            'event_type' => $validated['event_type'],
            'venue_name' => $validated['venue_name'],
            'event_date' => $validated['event_date'],
            'hourly_rate' => $validated['hourly_rate'],
            'status' => 'draft',
        ]);

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ], 201);
    }
}
