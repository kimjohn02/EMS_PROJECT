<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $date = request('date', now()->format('Y-m-d'));
        
        $attendances = Attendance::with('user.employee.department')
            ->where('date', $date)
            ->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $lateCount = $attendances->where('status', 'late')->count();

        // Note: For actual PDF/CSV export, we would use DOMPDF or Laravel Excel
        // For this prototype, we'll implement a basic print-friendly view mode
        
        return view('reports.index', compact(
            'attendances', 'departments', 'date', 'presentCount', 'absentCount', 'lateCount'
        ));
    }
}
