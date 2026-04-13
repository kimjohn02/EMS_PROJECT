<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Departments
        $departments = [
            ['name' => 'Information Technology', 'description' => 'IT and Software Development', 'head' => 'Juan dela Cruz'],
            ['name' => 'Human Resources',        'description' => 'HR and Recruitment',           'head' => 'Maria Santos'],
            ['name' => 'Finance',                'description' => 'Finance and Accounting',        'head' => 'Pedro Reyes'],
            ['name' => 'Operations',             'description' => 'Operations Management',         'head' => 'Ana Gonzales'],
            ['name' => 'Marketing',              'description' => 'Marketing and Communications',  'head' => 'Jose Ramos'],
        ];
        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Admin User
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@ems.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // HR Manager User
        $hr = User::create([
            'name'     => 'HR Manager',
            'email'    => 'hr@ems.com',
            'password' => Hash::make('password'),
            'role'     => 'hr',
        ]);
        Employee::create([
            'user_id'       => $hr->id,
            'department_id' => 2,
            'employee_id'   => 'EMP-002',
            'phone'         => '09171234568',
            'position'      => 'HR Manager',
            'date_hired'    => '2022-01-15',
            'status'        => 'active',
        ]);

        // Employee Users
        $employees = [
            ['name' => 'Juan dela Cruz',   'email' => 'juan@ems.com',   'dept' => 1, 'pos' => 'Software Developer',  'eid' => 'EMP-003'],
            ['name' => 'Maria Santos',     'email' => 'maria@ems.com',  'dept' => 3, 'pos' => 'Accountant',          'eid' => 'EMP-004'],
            ['name' => 'Pedro Reyes',      'email' => 'pedro@ems.com',  'dept' => 4, 'pos' => 'Operations Lead',     'eid' => 'EMP-005'],
            ['name' => 'Ana Gonzales',     'email' => 'ana@ems.com',    'dept' => 5, 'pos' => 'Marketing Specialist','eid' => 'EMP-006'],
            ['name' => 'Carlos Mendoza',   'email' => 'carlos@ems.com', 'dept' => 1, 'pos' => 'QA Engineer',         'eid' => 'EMP-007'],
        ];

        foreach ($employees as $emp) {
            $user = User::create([
                'name'     => $emp['name'],
                'email'    => $emp['email'],
                'password' => Hash::make('password'),
                'role'     => 'employee',
            ]);
            Employee::create([
                'user_id'       => $user->id,
                'department_id' => $emp['dept'],
                'employee_id'   => $emp['eid'],
                'phone'         => '091700000' . rand(10, 99),
                'position'      => $emp['pos'],
                'date_hired'    => Carbon::now()->subMonths(rand(3, 24))->format('Y-m-d'),
                'status'        => 'active',
            ]);
        }

        // Seed Attendance for last 7 days for all employees
        $allUsers = User::where('role', '!=', 'admin')->get();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            foreach ($allUsers as $user) {
                $present = rand(0, 4) > 0; // 80% chance present
                Attendance::create([
                    'user_id'  => $user->id,
                    'date'     => $date,
                    'time_in'  => $present ? '08:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00' : null,
                    'time_out' => $present ? '17:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00' : null,
                    'status'   => $present ? 'present' : 'absent',
                ]);
            }
        }
    }
}
