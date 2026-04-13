<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Admin and HR can access, hiding inactive
        $query = Employee::with(['user', 'department'])->where('status', '!=', 'inactive');
        
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(10);
        $archivedCount = Employee::where('status', 'inactive')->count();
        return view('employees.index', compact('employees', 'archivedCount'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('employees.form', compact('departments'));
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department']);
        $recentAttendance = \App\Models\Attendance::where('user_id', $employee->user_id)
            ->orderBy('date', 'desc')->take(7)->get();
        return view('employees.show', compact('employee', 'recentAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'date_hired' => 'required|date',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // default password
            'role' => 'employee',
        ]);

        $employeeId = 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);

        Employee::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'employee_id' => $employeeId,
            'phone' => $request->phone,
            'position' => $request->position,
            'date_hired' => $request->date_hired,
            'status' => 'active',
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully. Default password is: <strong>password</strong>. Please remind them to change it.');

    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        return view('employees.form', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$employee->user_id,
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'date_hired' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,on_leave',
        ]);

        $employee->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $employee->update([
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'position' => $request->position,
            'date_hired' => $request->date_hired,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    // Admins only (handled by routes)
    public function destroy(Employee $employee)
    {
        $employee->user->delete(); // Cascades to employee
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    // Archive employee — sets status to inactive without deleting
    public function archive(Employee $employee)
    {
        $employee->update(['status' => 'inactive']);
        return redirect()->route('employees.index')->with('success', 'Employee has been archived successfully.');
    }

    // View archived employees
    public function archivedList(Request $request)
    {
        $query = Employee::with(['user', 'department'])->where('status', 'inactive');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('employee_id', 'like', "%{$search}%");
        }

        $employees = $query->paginate(10);
        return view('employees.archived', compact('employees'));
    }

    // Restore employee — sets status back to active
    public function restore(Employee $employee)
    {
        $employee->update(['status' => 'active']);
        return redirect()->route('employees.archived')->with('success', 'Employee has been restored successfully.');
    }
}
