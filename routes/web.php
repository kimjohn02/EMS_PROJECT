<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;

// Authentication Routes
Route::get('/', function() {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    // Force Password Change Routes
    Route::get('/force-password-change', [AuthController::class, 'showForceChange'])->name('password.force-change');
    Route::post('/force-password-change', [AuthController::class, 'forceChange'])->name('password.force-change.store');

    Route::middleware(['\App\Http\Middleware\ForcePasswordChange'])->group(function () {
        // Shared Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // --- ADMIN MODULES ---
        Route::middleware(['admin'])->group(function () {
        // Departments (Full CRUD)
        Route::resource('departments', DepartmentController::class);
        
        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-csv', [ReportsController::class, 'exportCsv'])->name('reports.export-csv');
        
        // User Management (Admin / HR / Employee accounts)
        Route::resource('users', UserManagementController::class);
        
        // Employee Deletion (Admin only)
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        
        // Employee Archive (Admin only) — sets status to inactive
        Route::patch('employees/{employee}/archive', [EmployeeController::class, 'archive'])->name('employees.archive');

        // Archived employees (Admin only)
        Route::get('employees/archived', [EmployeeController::class, 'archivedList'])->name('employees.archived');
        Route::patch('employees/{employee}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    });

    // --- ADMIN & HR MODULES ---
    Route::middleware(['admin_or_hr'])->group(function () {
        // Employee Management
        Route::resource('employees', EmployeeController::class)->except(['destroy']);
    });

    // --- EMPLOYEE MODULES ---
    // Note: Profile and My Attendance are technically accessible by anyone logged in, 
    // but the views will adapt or only be linked for employees.
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/my-attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/time-in', [AttendanceController::class, 'timeIn'])->name('attendance.time-in');
    Route::post('/attendance/time-out', [AttendanceController::class, 'timeOut'])->name('attendance.time-out');
    
    // --- LEAVE MANAGEMENT MODULE (6TH MODULE) ---
    Route::get('/leaves', [App\Http\Controllers\LeaveRequestController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [App\Http\Controllers\LeaveRequestController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [App\Http\Controllers\LeaveRequestController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leave}', [App\Http\Controllers\LeaveRequestController::class, 'show'])->name('leaves.show');
    Route::patch('/leaves/{leave}/status', [App\Http\Controllers\LeaveRequestController::class, 'updateStatus'])->name('leaves.updateStatus');
    Route::patch('/leaves/{leave}/cancel', [App\Http\Controllers\LeaveRequestController::class, 'cancel'])->name('leaves.cancel');
    
    }); // End ForcePasswordChange
});
