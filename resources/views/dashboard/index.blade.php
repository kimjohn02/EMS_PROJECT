@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <h2 class="page-title">Dashboard Overview</h2>

    @if($user->isEmployee())
        <!-- EMPLOYEE DASHBOARD -->
        <div class="row mb-4">
            <!-- Attendance Rate Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100" style="border-left: 4px solid #10b981;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1"
                                    style="font-size: 0.8rem; font-weight: 700;">
                                    Attendance Rate (This Month)</div>
                                <div class="h3 mb-0 font-weight-bold text-dark">{{ $employeeAttendanceRate }}%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-chart-pie fa-2x text-muted" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hours Worked Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100" style="border-left: 4px solid #3b82f6;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                                    style="font-size: 0.8rem; font-weight: 700;">
                                    Hours Worked (This Month)</div>
                                <div class="h3 mb-0 font-weight-bold text-dark">{{ $employeeHoursWorked }} hrs</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-hourglass-end fa-2x text-muted" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Balance Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100" style="border-left: 4px solid #f59e0b;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                                    style="font-size: 0.8rem; font-weight: 700;">
                                    Vacation Days Left</div>
                                <div class="h3 mb-0 font-weight-bold text-dark">{{ $leaveBalance['vacation'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-plane fa-2x text-muted" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100" style="border-left: 4px solid #8b5cf6;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1"
                                    style="font-size: 0.8rem; font-weight: 700; color: #8b5cf6;">
                                    Pending Requests</div>
                                <div class="h3 mb-0 font-weight-bold text-dark">{{ $myPendingLeaves }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-clock fa-2x text-muted" style="opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Analytics Section -->
        <div class="row">
            <!-- Weekly Attendance Chart -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex flex-row align-items-center justify-content-between text-primary">
                        <h6 class="m-0 font-weight-bold">My Attendance - This Week</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($weeklyAttendance as $day)
                                <div class="col">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="fa-solid fa-circle fa-2x 
                                                    @if($day['status'] === 'present')
                                                        text-success
                                                    @elseif($day['status'] === 'absent')
                                                        text-danger
                                                    @elseif($day['status'] === 'late')
                                                        text-warning
                                                    @else
                                                        text-muted
                                                    @endif
                                                "></i>
                                        </div>
                                        <small class="text-muted">{{ $day['date'] }}</small>
                                        <div style="font-size: 0.75rem; font-weight: 600; text-transform: capitalize;">
                                            @if($day['status'] === 'no-record')
                                                Not recorded
                                            @else
                                                {{ $day['status'] }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Info Card -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h6 class="m-0 text-primary font-weight-bold">My Info</h6>
                    </div>
                    <div class="card-body">
                        @if($lastCheckIn)
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Last Check-In</small>
                                <strong class="d-block">{{ $lastCheckIn->date->format('M d, Y') }}</strong>
                                @if($lastCheckIn->time_in)
                                    <small class="text-success"><i class="fa-solid fa-check-circle me-1"></i>
                                        {{ \Carbon\Carbon::parse($lastCheckIn->time_in)->format('h:i A') }}</small>
                                @endif
                            </div>
                        @endif

                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted d-block mb-1">Sick Days Left</small>
                            <strong class="d-block">{{ $leaveBalance['sick'] }} days</strong>
                        </div>

                        <div>
                            <small class="text-muted d-block mb-1">Emergency Leave Left</small>
                            <strong class="d-block">{{ $leaveBalance['emergency'] }} days</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <h6 class="mb-3 font-weight-bold">Quick Actions</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('attendance.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-calendar-check me-2"></i> View My Attendance
                    </a>
                    <a href="{{ route('leaves.create') }}" class="btn btn-success">
                        <i class="fa-solid fa-plus me-2"></i> Request Leave
                    </a>
                    <a href="{{ route('leaves.index') }}" class="btn btn-info">
                        <i class="fa-solid fa-list me-2"></i> My Leave Requests
                    </a>
                </div>
            </div>
        </div>

    @else
        <!-- ADMIN/HR DASHBOARD -->
        <!-- ADMIN/HR DASHBOARD -->
        <!-- HR/Admin Stats Cards -->
        <style>
            .stat-card-link {
                text-decoration: none;
                color: inherit;
                display: flex;
                height: 100%;
            }

            .stat-card-link .card {
                transition: transform 0.15s ease, box-shadow 0.15s ease;
                cursor: pointer;
                flex: 1;
            }

            .stat-card-link:hover .card {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12) !important;
            }

            .stat-card-label {
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                margin-bottom: 0.25rem;
            }
            .stat-card-link .row {
                flex-wrap: nowrap;
            }
            .stat-card-link .col {
                min-width: 0;
            }
        </style>
        <div class="row mb-3">
            <!-- Present Today -->
            <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
                <a href="{{ route('attendance.index') }}" class="stat-card-link">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #10b981;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1 stat-card-label">
                                        Present Today</div>
                                    <div class="h3 mb-0 font-weight-bold text-dark">{{ $presentToday }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-user-check fa-2x text-muted" style="opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Absent Today -->
            <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
                <a href="{{ route('attendance.index') }}" class="stat-card-link">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #ef4444;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 stat-card-label">
                                        Absent Today</div>
                                    <div class="h3 mb-0 font-weight-bold text-dark">{{ $absentToday }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-user-xmark fa-2x text-muted" style="opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Late Today -->
            <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
                <a href="{{ route('attendance.index') }}" class="stat-card-link">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #f59e0b;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 stat-card-label">
                                        Late Today</div>
                                    <div class="h3 mb-0 font-weight-bold text-dark">{{ $lateToday }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-hourglass-end fa-2x text-muted" style="opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Employees -->
            <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
                <a href="{{ route('employees.index') }}" class="stat-card-link">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #3b82f6;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 stat-card-label">
                                        Total Employees</div>
                                    <div class="h3 mb-0 font-weight-bold text-dark">{{ $totalEmployees }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-users fa-2x text-muted" style="opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Departments -->
            <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
                <a href="{{ route('departments.index') }}" class="stat-card-link">
                    <div class="card shadow-sm h-100" style="border-left: 4px solid #f59e0b;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 stat-card-label">
                                        Departments</div>
                                    <div class="h3 mb-0 font-weight-bold text-dark">{{ $totalDepartments }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-building fa-2x text-muted" style="opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pending Leaves -->
            @if($user->role === 'admin' || $user->role === 'hr')
                <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('leaves.index') }}" class="stat-card-link">
                        <div class="card shadow-sm h-100" style="border-left: 4px solid #8b5cf6;">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1 stat-card-label"
                                            style="color: #8b5cf6;">Pending Leaves</div>
                                        <div class="h3 mb-0 font-weight-bold text-dark">{{ $pendingLeaves }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa-solid fa-clock fa-2x text-muted" style="opacity: 0.3;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        </div>

        @if($user->role === 'admin' || $user->role === 'hr')
            <div class="row mt-2">
                <!-- Attendance Chart -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h6 class="m-0 fw-bolder text-dark">Attendance Overview (Last 7 Days)</h6>
                        </div>
                        <div class="card-body" style="position: relative; height: 220px; width: 100%;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Department Breakdown -->
                @if($user->role === 'admin')
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h6 class="m-0 fw-bolder text-dark">Employees by Department</h6>
                            </div>
                            <div class="card-body">
                                @foreach($departmentStats as $dept)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem;">
                                            <span>{{ $dept->name }}</span>
                                            <span class="text-muted">{{ $dept->employees_count }} / {{ $totalEmployees }}</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            @php $pct = $totalEmployees > 0 ? ($dept->employees_count / $totalEmployees) * 100 : 0; @endphp
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%"
                                                aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Leave Requests -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bolder text-dark">Recent Leave Requests</h6>
                            <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body p-0">
                            @if($recentActivities->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentActivities as $leave)
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                            <div>
                                                <div class="fw-bold">{{ $leave->user->name }}</div>
                                                <small class="text-muted text-capitalize">{{ $leave->type }} &mdash;
                                                    {{ $leave->start_date->format('M d, Y') }} to
                                                    {{ $leave->end_date->format('M d, Y') }}</small>
                                            </div>
                                            <div>
                                                @if($leave->status === 'pending')
                                                    <span class="badge-status badge-late">Pending</span>
                                                @elseif($leave->status === 'approved')
                                                    <span class="badge-status badge-present">Approved</span>
                                                @else
                                                    <span class="badge-status badge-absent">Rejected</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-regular fa-inbox fa-2x mb-2 d-block" style="opacity:0.3;"></i>
                                    <p class="mb-0">No recent leave requests</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Today's Summary -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="m-0 fw-bolder text-dark">Today's Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Check-In Rate</span>
                                    @php
                                        $checkInRate = $totalEmployees > 0 ? round((($presentToday + $lateToday) / $totalEmployees) * 100) : 0;
                                    @endphp
                                    <strong>{{ $checkInRate }}%</strong>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $checkInRate }}%"
                                        aria-valuenow="{{ $checkInRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">{{ $presentToday + $lateToday }} out of {{ $totalEmployees }} checked
                                    in</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection

@section('scripts')
    @if($user->role === 'admin' || $user->role === 'hr')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var ctx = document.getElementById("attendanceChart");
                if (ctx) {
                    var myLineChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($chartLabels) !!},
                            datasets: [{
                                label: "Present",
                                backgroundColor: "#10b981",
                                hoverBackgroundColor: "#059669",
                                data: {!! json_encode($chartPresent) !!},
                            },
                            {
                                label: "Absent",
                                backgroundColor: "#ef4444",
                                hoverBackgroundColor: "#dc2626",
                                data: {!! json_encode($chartAbsent) !!},
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endif
@endsection