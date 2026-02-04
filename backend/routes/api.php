<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventShiftController;
use App\Http\Controllers\ShiftApplicationController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Organizer Routes
    Route::post('/events', [EventController::class, 'store']);
    Route::post('/events/{event}/shifts', [EventShiftController::class, 'store']);
    Route::post('/applications/{application}/decision', [ShiftApplicationController::class, 'decide']);
    Route::get('/my/applications', [ShiftApplicationController::class, 'myApplications']);
    Route::get('/my/shifts', [ShiftApplicationController::class, 'myShifts']);
    Route::post('/applications/{application}/no-show', [ShiftApplicationController::class, 'noShow']);
    Route::post('/applications/{application}/complete', [ShiftApplicationController::class, 'completeShift']);

    // Employee Routes
    Route::post('/shifts/{shift}/apply', [ShiftApplicationController::class, 'apply']);
});
