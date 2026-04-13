@extends('layouts.app')
@section('title', 'Employee Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Employees
            </a>
            <h2 class="page-title mb-0">Employee Details</h2>
        </div>
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Employee
        </a>
    </div>

    <div class="row">
        {{-- LEFT: Profile Card --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm text-center p-4">
                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($employee->user->name, 0, 1) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $employee->user->name }}</h5>
                <p class="text-muted mb-1" style="font-size:0.9rem;">{{ $employee->position }}</p>
                <span class="badge-status {{ $employee->status === 'active' ? 'badge-present' : ($employee->status === 'on_leave' ? 'badge-late' : 'badge-absent') }} mb-3">
                    {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                </span>
                <hr>
                <div class="text-start">
                    <p class="mb-2" style="font-size:0.9rem;"><i class="fa-solid fa-id-badge text-primary me-2"></i><strong>ID:</strong> {{ $employee->employee_id }}</p>
                    <p class="mb-2" style="font-size:0.9rem;"><i class="fa-solid fa-envelope text-primary me-2"></i><strong>Email:</strong> {{ $employee->user->email }}</p>
                    <p class="mb-2" style="font-size:0.9rem;"><i class="fa-solid fa-phone text-primary me-2"></i><strong>Phone:</strong> {{ $employee->phone ?? 'N/A' }}</p>
                    <p class="mb-2" style="font-size:0.9rem;"><i class="fa-solid fa-building text-primary me-2"></i><strong>Department:</strong> {{ $employee->department ? $employee->department->name : 'N/A' }}</p>
                    <p class="mb-2" style="font-size:0.9rem;"><i class="fa-solid fa-calendar text-primary me-2"></i><strong>Date Hired:</strong> {{ $employee->date_hired ? $employee->date_hired->format('M d, Y') : 'N/A' }}</p>
                    <p class="mb-0" style="font-size:0.9rem;"><i class="fa-solid fa-location-dot text-primary me-2"></i><strong>Address:</strong> {{ $employee->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- RIGHT: Recent Attendance --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="m-0 text-primary font-weight-bold"><i class="fa-solid fa-calendar-check me-2"></i>Recent Attendance (Last 7 Days)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendance as $att)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($att->date)->format('M d, Y (D)') }}</td>
                                        <td>{{ $att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('h:i A') : '--' }}</td>
                                        <td>{{ $att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('h:i A') : '--' }}</td>
                                        <td>
                                            @if($att->status === 'present')
                                                <span class="badge-status badge-present">Present</span>
                                            @elseif($att->status === 'late')
                                                <span class="badge-status badge-late">Late</span>
                                            @else
                                                <span class="badge-status badge-absent">Absent</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No attendance records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
