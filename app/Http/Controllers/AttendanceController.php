<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isHR()) {
            $attendances = Attendance::with('user')->orderBy('date', 'desc')->paginate(10);
            return view('attendance.index', compact('attendances'));
        }

        // Employee view
        $attendances = Attendance::where('user_id', $user->id)->orderBy('date', 'desc')->paginate(10);
        $today = Carbon::today()->toDateString();
        $todayAttendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        return view('attendance.index', compact('attendances', 'todayAttendance'));
    }

    public function timeIn(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $timeNow = Carbon::now()->toTimeString();

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'present']
        );

        if (!$attendance->time_in) {
            $attendance->update(['time_in' => $timeNow]);
            return back()->with('success', 'Successfully Timed In at ' . $timeNow);
        }

        return back()->with('error', 'Already Timed In today.');
    }

    public function timeOut(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $timeNow = Carbon::now()->toTimeString();

        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if ($attendance && $attendance->time_in && !$attendance->time_out) {
            $timeIn = Carbon::parse($attendance->time_in);
            if ($timeIn->diffInMinutes(Carbon::now()) < 5) {
                return back()->with('error', 'You must wait at least 5 minutes before timing out.');
            }

            $attendance->update(['time_out' => $timeNow]);
            return back()->with('success', 'Successfully Timed Out at ' . $timeNow);
        }

        return back()->with('error', 'Cannot Time Out. Please check your time records.');
    }
}
