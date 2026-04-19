@extends('layouts.app')
@section('title', 'Attendance Tracking')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0 fw-bolder">Monitor Attendance</h2>
    </div>

    @php $role = auth()->user()->role; @endphp

    @if($role === 'employee')
        <div class="row mb-5">
            <div class="col-lg-6 mx-auto">
                <div class="card shadow-sm text-center">
                    <div class="card-body p-5">
                        <h4 class="mb-2 fw-bold text-dark">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</h4>
                        <div class="display-4 fw-bold text-primary mb-4" id="clock">{{ \Carbon\Carbon::now()->format('H:i:s') }}
                        </div>

                        @if($todayAttendance)
                            @if($todayAttendance->time_in && !$todayAttendance->time_out)
                                <div class="alert alert-success mt-3 mb-4 d-inline-block">
                                    <i class="fa-solid fa-check-circle me-1"></i> Timed In at
                                    {{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') }}
                                </div>
                                <form action="{{ route('attendance.time-out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-5 text-dark fw-bold rounded-pill shadow-sm">
                                        <i class="fa-solid fa-door-open me-2"></i> Time Out Now
                                    </button>
                                </form>
                            @elseif($todayAttendance->time_in && $todayAttendance->time_out)
                                <div class="alert alert-info mt-3 d-inline-block">
                                    <i class="fa-solid fa-clipboard-check me-1"></i> Attendance Complete for Today
                                    <br>
                                    <small>In: {{ \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A') }} | Out:
                                        {{ \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A') }}</small>
                                </div>
                            @endif
                        @else
                            <form action="{{ route('attendance.time-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold rounded-pill shadow-sm">
                                    <i class="fa-solid fa-fingerprint me-2"></i> Time In Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Live clock
            setInterval(function () {
                var now = new Date();
                var hours = now.getHours().toString().padStart(2, '0');
                var minutes = now.getMinutes().toString().padStart(2, '0');
                var seconds = now.getSeconds().toString().padStart(2, '0');
                document.getElementById('clock').innerText = hours + ":" + minutes + ":" + seconds;
            }, 1000);
        </script>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <h6 class="m-0 fw-bolder text-dark">
                {{ $role === 'employee' ? 'Attendance Records' : 'Employee Attendance Logs' }}</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            @if($role === 'admin' || $role === 'hr')
                                <th class="fw-bolder text-dark">ID</th>
                                <th class="fw-bolder text-dark">Employee Name</th>
                            @endif
                            <th class="fw-bolder text-dark">Date</th>
                            <th class="fw-bolder text-dark">Time In</th>
                            <th class="fw-bolder text-dark">Time Out</th>
                            <th class="fw-bolder text-dark">Hours Worked</th>
                            <th class="fw-bolder text-dark">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $log)
                            <tr>
                                @if($role === 'admin' || $role === 'hr')
                                    <td class="fw-normal">
                                        {{ $log->user->employee ? (int) preg_replace('/[^0-9]/', '', $log->user->employee->employee_id) : 'N/A' }}
                                    </td>
                                    <td class="fw-normal">
                                        {{ $log->user->name }}
                                    </td>
                                @endif
                                <td class="fw-normal">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                                <td class="fw-normal">
                                    {{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i A') : 'No Log' }}
                                </td>
                                <td class="fw-normal">
                                    {{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : 'No Log' }}
                                </td>
                                <td class="fw-normal">
                                    @if($log->time_in && $log->time_out)
                                        @php
                                            $diff = \Carbon\Carbon::parse($log->time_in)->diff(\Carbon\Carbon::parse($log->time_out));
                                            $total = $diff->format('%h hr %i min');
                                        @endphp
                                        <span>{{ $total }}</span>
                                    @else
                                        <span class="fw-normal">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->status === 'present')
                                        <span class="badge-status badge-present">Present</span>
                                    @elseif($log->status === 'absent')
                                        <span class="badge-status badge-absent">Absent</span>
                                    @elseif($log->status === 'late')
                                        <span class="badge-status badge-late">Late</span>
                                    @else
                                        <span class="badge-status badge-active">{{ ucfirst($log->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($role === 'admin' || $role === 'hr') ? 6 : 5 }}"
                                    class="text-center py-4 text-muted">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($attendances->hasPages())
            <div class="card-footer bg-white border-top border-light">
                {{ $attendances->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection