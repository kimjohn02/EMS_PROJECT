<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::all();
        $dateFrom = $request->input('date_from', now()->format('Y-m-d'));
        $dateTo   = $request->input('date_to',   now()->format('Y-m-d'));

        $attendances = Attendance::with('user.employee.department')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'asc')
            ->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $lateCount    = $attendances->where('status', 'late')->count();

        return view('reports.index', compact(
            'attendances', 'departments', 'dateFrom', 'dateTo',
            'presentCount', 'absentCount', 'lateCount'
        ));
    }

    public function exportCsv(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->format('Y-m-d'));
        $dateTo   = $request->input('date_to',   now()->format('Y-m-d'));

        $attendances = Attendance::with('user.employee.department')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'asc')
            ->get();

        $filename = 'attendance_report_' . $dateFrom . '_to_' . $dateTo . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            // CSV Header row
            fputcsv($handle, ['Employee ID', 'Name', 'Department', 'Date', 'Time In', 'Time Out', 'Hours Worked', 'Status']);

            foreach ($attendances as $log) {
                $emp    = $log->user->employee ?? null;
                $timeIn  = $log->time_in  ? \Carbon\Carbon::parse($log->time_in)->format('h:i A')  : 'No Log';
                $timeOut = $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : 'No Log';

                if ($log->time_in && $log->time_out) {
                    $diff  = \Carbon\Carbon::parse($log->time_in)->diff(\Carbon\Carbon::parse($log->time_out));
                    $hours = $diff->format('%h hr %i min');
                } else {
                    $hours = 'N/A';
                }

                fputcsv($handle, [
                    $emp ? $emp->employee_id : 'N/A',
                    $log->user->name,
                    ($emp && $emp->department) ? $emp->department->name : 'N/A',
                    \Carbon\Carbon::parse($log->date)->format('M d, Y'),
                    $timeIn,
                    $timeOut,
                    $hours,
                    ucfirst($log->status),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
