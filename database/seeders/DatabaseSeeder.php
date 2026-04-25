<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Departments
        $departments = [
            ['name' => 'Information Technology', 'description' => 'IT and Software Development', 'head' => 'Juan dela Cruz'],
            ['name' => 'Human Resources', 'description' => 'HR and Recruitment', 'head' => 'Maria Santos'],
            ['name' => 'Finance', 'description' => 'Finance and Accounting', 'head' => 'Pedro Reyes'],
            ['name' => 'Operations', 'description' => 'Operations Management', 'head' => 'Ana Gonzales'],
            ['name' => 'Marketing', 'description' => 'Marketing and Communications', 'head' => 'Jose Ramos'],
        ];
        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ems.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // HR Manager User
        $hr = User::create([
            'name' => 'HR Manager',
            'email' => 'hr@ems.com',
            'password' => Hash::make('password'),
            'role' => 'hr',
        ]);
        Employee::create([
            'user_id' => $hr->id,
            'department_id' => 2,
            'employee_id' => 'EMP-002',
            'phone' => '09171234568',
            'position' => 'HR Manager',
            'date_hired' => '2022-01-15',
            'status' => 'active',
        ]);

        // Employee Users
        $employees = [
            ['name' => 'Juan dela Cruz', 'email' => 'juan@ems.com', 'dept' => 1, 'pos' => 'Software Developer', 'eid' => 'EMP-003'],
            ['name' => 'Maria Santos', 'email' => 'maria@ems.com', 'dept' => 3, 'pos' => 'Accountant', 'eid' => 'EMP-004'],
            ['name' => 'Pedro Reyes', 'email' => 'pedro@ems.com', 'dept' => 4, 'pos' => 'Operations Lead', 'eid' => 'EMP-005'],
            ['name' => 'Ana Gonzales', 'email' => 'ana@ems.com', 'dept' => 5, 'pos' => 'Marketing Specialist', 'eid' => 'EMP-006'],
            ['name' => 'Carlos Mendoza', 'email' => 'carlos@ems.com', 'dept' => 1, 'pos' => 'QA Engineer', 'eid' => 'EMP-007'],
            ['name' => 'Liza Fernandez', 'email' => 'liza@ems.com', 'dept' => 2, 'pos' => 'HR Associate', 'eid' => 'EMP-008'],
            ['name' => 'Mark Villanueva', 'email' => 'mark@ems.com', 'dept' => 1, 'pos' => 'Frontend Developer', 'eid' => 'EMP-009'],
            ['name' => 'Rina Bautista', 'email' => 'rina@ems.com', 'dept' => 3, 'pos' => 'Financial Analyst', 'eid' => 'EMP-010'],
            ['name' => 'Noel Garcia', 'email' => 'noel@ems.com', 'dept' => 4, 'pos' => 'Operations Officer', 'eid' => 'EMP-011'],
            ['name' => 'Joy Lim', 'email' => 'joy@ems.com', 'dept' => 5, 'pos' => 'Content Strategist', 'eid' => 'EMP-012'],
            ['name' => 'Paolo Diaz', 'email' => 'paolo@ems.com', 'dept' => 1, 'pos' => 'Backend Developer', 'eid' => 'EMP-013'],
            ['name' => 'Trisha Cruz', 'email' => 'trisha@ems.com', 'dept' => 2, 'pos' => 'Recruitment Specialist', 'eid' => 'EMP-014'],
            ['name' => 'Ivan Mendoza', 'email' => 'ivan@ems.com', 'dept' => 4, 'pos' => 'Logistics Coordinator', 'eid' => 'EMP-015'],
            ['name' => 'Sophie Ramos', 'email' => 'sophie@ems.com', 'dept' => 5, 'pos' => 'Brand Manager', 'eid' => 'EMP-016'],
            ['name' => 'Kevin Tan', 'email' => 'kevin@ems.com', 'dept' => 3, 'pos' => 'Payroll Specialist', 'eid' => 'EMP-017'],
        ];

        foreach ($employees as $emp) {
            $user = User::create([
                'name' => $emp['name'],
                'email' => $emp['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]);
            Employee::create([
                'user_id' => $user->id,
                'department_id' => $emp['dept'],
                'employee_id' => $emp['eid'],
                'phone' => '091700000' . rand(10, 99),
                'position' => $emp['pos'],
                'date_hired' => Carbon::now()->subMonths(rand(3, 24))->format('Y-m-d'),
                'status' => 'active',
            ]);
        }

        // Seed Attendance for last 7 days for all employees
        $allUsers = User::where('role', '!=', 'admin')->get();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            foreach ($allUsers as $user) {
                $present = rand(0, 4) > 0; // 80% chance present
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'time_in' => $present ? '08:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00' : null,
                    'time_out' => $present ? '17:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00' : null,
                    'status' => $present ? 'present' : 'absent',
                ]);
            }
        }

        // Seed Leave Requests for non-admin users
        $leaveTypes = ['vacation', 'sick', 'emergency', 'other'];
        $leaveReasons = [
            'Medical checkup and recovery.',
            'Family emergency assistance.',
            'Planned vacation leave.',
            'Personal matters requiring time off.',
            'Attending urgent legal appointment.',
        ];
        $leaveUsers = User::where('role', '!=', 'admin')->get();

        foreach ($leaveUsers as $user) {
            if (rand(0, 1) === 0) {
                continue;
            }

            $startDate = Carbon::now()->subDays(rand(1, 45));
            $duration = rand(1, 3);
            $status = ['pending', 'approved', 'rejected'][rand(0, 2)];

            LeaveRequest::create([
                'user_id' => $user->id,
                'type' => $leaveTypes[array_rand($leaveTypes)],
                'start_date' => $startDate->toDateString(),
                'end_date' => $startDate->copy()->addDays($duration)->toDateString(),
                'reason' => $leaveReasons[array_rand($leaveReasons)],
                'status' => $status,
                'rejection_reason' => $status === 'rejected' ? 'Insufficient leave balance for requested period.' : null,
            ]);
        }
    }
}