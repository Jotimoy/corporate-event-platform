<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShiftApplication;
use App\Models\EventShift;

class ShiftApplicationController extends Controller
                public function completeShift(Request $request, ShiftApplication $application)
                {
                    $user = $request->user();
                    if ($user->role !== 'organizer') {
                        return response()->json(['message' => 'Only organizers can complete shifts'], 403);
                    }
                    if ($application->status !== 'approved') {
                        return response()->json(['message' => 'Only approved applications can be completed'], 409);
                    }

                    $application->status = 'completed';
                    $application->save();

                    $shift = $application->shift;
                    $event = $shift->event;

                    // Calculate hours worked
                    $start = strtotime($shift->start_time);
                    $end = strtotime($shift->end_time);
                    $hours_worked = ($end - $start) / 3600;
                    if ($hours_worked < 0) $hours_worked = 0;

                    $hourly_rate = $event->hourly_rate;
                    $total_amount = $hours_worked * $hourly_rate;

                    $compensation = \App\Models\Compensation::create([
                        'user_id' => $application->user_id,
                        'event_shift_id' => $shift->id,
                        'hours_worked' => $hours_worked,
                        'hourly_rate' => $hourly_rate,
                        'total_amount' => $total_amount,
                        'status' => 'pending',
                    ]);

                    return response()->json([
                        'message' => 'Shift completed and compensation created',
                        'application' => $application,
                        'compensation' => $compensation
                    ], 200);
                }
            public function noShow(Request $request, ShiftApplication $application)
            {
                $user = $request->user();
                if ($user->role !== 'organizer') {
                    return response()->json(['message' => 'Only organizers can mark no-show'], 403);
                }
                if ($application->status !== 'approved') {
                    return response()->json(['message' => 'No-show can only be marked on approved applications'], 409);
                }
                $application->status = 'no_show';
                $application->save();
                return response()->json([
                    'message' => 'Application marked as no-show',
                    'application' => $application
                ], 200);
            }
        public function myApplications(Request $request)
        {
            $user = $request->user();
            if ($user->role !== 'employee') {
                return response()->json(['message' => 'Only employees can view their applications'], 403);
            }

            $applications = \App\Models\ShiftApplication::with(['shift.event'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['applied', 'approved', 'rejected'])
                ->orderByDesc('created_at')
                ->get();

            return response()->json(['applications' => $applications], 200);
        }

        public function myShifts(Request $request)
        {
            $user = $request->user();
            if ($user->role !== 'employee') {
                return response()->json(['message' => 'Only employees can view their shifts'], 403);
            }

            $shifts = \App\Models\ShiftApplication::with(['shift.event'])
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->orderByDesc('created_at')
                ->get();

            return response()->json(['shifts' => $shifts], 200);
        }
    public function decide(Request $request, ShiftApplication $application)
    {
        $user = $request->user();
        if ($user->role !== 'organizer') {
            return response()->json(['message' => 'Only organizers can make decisions'], 403);
        }

        $validated = $request->validate([
            'decision' => 'required|in:approved,rejected',
        ]);

        if ($application->status !== 'applied') {
            return response()->json(['message' => 'Decision already made'], 409);
        }

        $application->status = $validated['decision'];
        $application->save();

        return response()->json([
            'message' => 'Application ' . $validated['decision'],
            'application' => $application
        ], 200);
    }
{
    public function apply(Request $request, $shift)
    {
        $user = $request->user();
        if ($user->role !== 'employee') {
            return response()->json(['message' => 'Only employees can apply for shifts'], 403);
        }

        $MAX_NO_SHOW = 3;
        $noShowCount = \App\Models\ShiftApplication::where('user_id', $user->id)
            ->where('status', 'no_show')
            ->count();
        if ($noShowCount >= $MAX_NO_SHOW) {
            return response()->json(['message' => 'You are blocked from applying due to repeated no-shows.'], 403);
        }

        $eventShift = \App\Models\EventShift::find($shift);
        if (!$eventShift) {
            return response()->json(['message' => 'Shift not found'], 404);
        }
        if ($eventShift->status !== 'open') {
            return response()->json(['message' => 'Shift is not open'], 409);
        }

        $alreadyApplied = \App\Models\ShiftApplication::where('event_shift_id', $shift)
            ->where('user_id', $user->id)
            ->exists();
        if ($alreadyApplied) {
            return response()->json(['message' => 'Already applied'], 409);
        }

        $application = \App\Models\ShiftApplication::create([
            'event_shift_id' => $shift,
            'user_id' => $user->id,
            'status' => 'applied',
        ]);

        $eventShift->required_employee = $eventShift->required_employee - 1;
        if ($eventShift->required_employee <= 0) {
            $eventShift->status = 'full';
            $eventShift->required_employee = 0;
        }
        $eventShift->save();

        return response()->json([
            'message' => 'Applied successfully',
            'application' => $application,
            'shift' => $eventShift
        ], 201);
    }
}
