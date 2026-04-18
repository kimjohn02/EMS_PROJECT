<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {

        $query = Employee::with(['user', 'department'])->where('status', '!=', 'archived');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(10);
        $archivedCount = Employee::where('status', 'archived')->count();
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

    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'), // default password
            'role' => 'employee',
            'requires_password_change' => true,
        ]);

        $employeeId = 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);

        Employee::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'employee_id' => $employeeId,
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'],
            'date_hired' => $validated['date_hired'],
            'status' => 'active',
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully. Default password is: <strong>password</strong>. Please remind them to change it.');

    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        return view('employees.form', compact('employee', 'departments'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

        $employee->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $employee->update([
            'department_id' => $validated['department_id'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'],
            'date_hired' => $validated['date_hired'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    // Admins only (handled by routes)
    public function destroy(Employee $employee)
    {
        $user = $employee->user;
        $employee->delete();
        if ($user) {
            $user->delete();
        }
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    // Archive employee — sets status to inactive without deleting
    public function archive(Employee $employee)
    {
        $employee->update(['status' => 'archived']);
        return redirect()->route('employees.index')->with('success', 'Employee has been archived successfully.');
    }

    // View archived employees
    public function archivedList(Request $request)
    {
        $query = Employee::with(['user', 'department'])->where('status', 'archived');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('employee_id', 'like', "%{$search}%");
        }

        $employees = $query->paginate(10);
        return view('employees.archived', compact('employees'));
    }

    public function restore(Employee $employee)
    {
        $employee->update(['status' => 'active']);
        return redirect()->route('employees.archived')->with('success', 'Employee has been restored successfully.');
    }
}
