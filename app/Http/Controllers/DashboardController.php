<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user instanceof User) {
            return redirect()->route('login');
        }
        $today = Carbon::today()->toDateString();
        $thisMonth = Carbon::now()->format('Y-m');

        // Admin/HR dashboard data
        $totalEmployees  = User::where('role', 'employee')->count();
        $totalDepartments = Department::count();
        $presentToday    = Attendance::where('date', $today)->where('status', 'present')->count();
        $absentToday     = Attendance::where('date', $today)->where('status', 'absent')->count();
        $lateToday       = Attendance::where('date', $today)->where('status', 'late')->count();
        $pendingLeaves   = LeaveRequest::where('status', 'pending')->count();

        // Employee-specific data
        $employeeAttendanceRate = 0;
        $employeeHoursWorked = 0;
        $leaveBalance = ['vacation' => 0, 'sick' => 0, 'emergency' => 0];
        $myPendingLeaves = 0;
        $lastCheckIn = null;
        $weeklyAttendance = [];

        if ($user->isEmployee()) {
            // Calculate this month's attendance rate
            $monthStart = Carbon::now()->startOfMonth()->format('Y-m-d');
            $monthEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
            $totalWorkDays = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->count();
            $presentDays = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->whereIn('status', ['present', 'late'])
                ->count();
            $employeeAttendanceRate = $totalWorkDays > 0 ? round(($presentDays / $totalWorkDays) * 100) : 0;

            // Calculate hours worked this month
            $attendances = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get();
            foreach ($attendances as $att) {
                if ($att->time_in && $att->time_out) {
                    $diff = Carbon::parse($att->time_in)->diffInMinutes(Carbon::parse($att->time_out));
                    $employeeHoursWorked += $diff;
                }
            }
            $employeeHoursWorked = round($employeeHoursWorked / 60, 1);

            // Calculate leave balance (assuming 15 vacation, 10 sick, 5 emergency per year)
            $allLeaves = LeaveRequest::where('user_id', $user->id)
                ->whereYear('start_date', Carbon::now()->year)
                ->where('status', 'approved')
                ->get();
            
            $leaveBalance = [
                'vacation' => 15,
                'sick' => 10,
                'emergency' => 5
            ];
            
            foreach ($allLeaves as $leave) {
                /** @var string $startDateStr */
                $startDateStr = $leave->start_date;
                /** @var string $endDateStr */
                $endDateStr = $leave->end_date;
                $startDate = new Carbon($startDateStr);
                $endDate = new Carbon($endDateStr);
                $days = $startDate->diffInDays($endDate) + 1;
                if (isset($leaveBalance[$leave->type])) {
                    $leaveBalance[$leave->type] -= $days;
                }
            }

            // My pending leaves
            $myPendingLeaves = LeaveRequest::where('user_id', $user->id)->where('status', 'pending')->count();

            // Last check-in
            $lastCheckIn = Attendance::where('user_id', $user->id)->latest('date')->first();

            // Weekly attendance
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $dayName = Carbon::now()->subDays($i)->format('D');
                $attendance = Attendance::where('user_id', $user->id)
                    ->where('date', $date)
                    ->first();
                $weeklyAttendance[] = [
                    'date' => $dayName,
                    'status' => $attendance ? $attendance->status : 'no-record'
                ];
            }
        }

        // Chart data for last 7 days (admin only)
        $chartLabels = [];
        $chartPresent = [];
        $chartAbsent = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[]  = Carbon::now()->subDays($i)->format('M d');
            $chartPresent[] = Attendance::where('date', $date)->where('status', 'present')->count();
            $chartAbsent[]  = Attendance::where('date', $date)->where('status', 'absent')->count();
        }

        // Role-specific dept breakdown
        $departmentStats = Department::withCount('employees')->get();

        // Recent activities
        $recentActivities = LeaveRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalEmployees', 'totalDepartments', 'presentToday', 'absentToday', 'lateToday', 'pendingLeaves',
            'chartLabels', 'chartPresent', 'chartAbsent', 'departmentStats', 'user', 'recentActivities',
            'employeeAttendanceRate', 'employeeHoursWorked', 'leaveBalance', 'myPendingLeaves', 'lastCheckIn', 'weeklyAttendance'
        ));
    }
}
