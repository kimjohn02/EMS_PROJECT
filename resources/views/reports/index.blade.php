@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')

    {{-- ===== Page Header ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h2 class="page-title mb-0 fw-bolder">Attendance Report</h2>
        <div class="d-flex gap-2">
            {{-- Export CSV --}}
            <a href="{{ route('reports.export-csv', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
               class="btn btn-success">
                <i class="fa-solid fa-file-csv me-1"></i> Export CSV
            </a>
            {{-- Print --}}
            <button type="button" class="btn btn-secondary" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Print Report
            </button>
        </div>
    </div>

    {{-- ===== Print Header (hidden on screen, shown when printing) ===== --}}
    <div class="d-none d-print-block text-center mb-4">
        <h2>EMS - Employee Management System</h2>
        <h4>Attendance Report</h4>
        <p class="text-muted">
            @if($dateFrom === $dateTo)
                {{ \Carbon\Carbon::parse($dateFrom)->format('F d, Y') }}
            @else
                {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} &mdash; {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            @endif
        </p>
        <hr>
    </div>

    {{-- ===== Filter Form ===== --}}
    <div class="card shadow-sm border-0 rounded-4 d-print-none mb-4">
        <div class="card-body py-3">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date_from" class="form-label fw-semibold text-muted" style="font-size:0.85rem;">
                        <i class="fa-solid fa-calendar-day me-1"></i> Date From
                    </label>
                    <input type="date" id="date_from" name="date_from"
                           class="form-control" value="{{ $dateFrom }}" required>
                </div>
                <div class="col-md-4">
                    <label for="date_to" class="form-label fw-semibold text-muted" style="font-size:0.85rem;">
                        <i class="fa-solid fa-calendar-day me-1"></i> Date To
                    </label>
                    <input type="date" id="date_to" name="date_to"
                           class="form-control" value="{{ $dateTo }}" required>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Summary Cards ===== --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card bg-success text-white border-0 rounded-4 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3 px-4">
                    <div>
                        <div class="small fw-semibold text-white-50 text-uppercase">Present</div>
                        <h2 class="display-5 fw-bold mb-0">{{ $presentCount }}</h2>
                    </div>
                    <i class="fa-solid fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card bg-warning text-dark border-0 rounded-4 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3 px-4">
                    <div>
                        <div class="small fw-semibold opacity-75 text-uppercase">Late</div>
                        <h2 class="display-5 fw-bold mb-0">{{ $lateCount }}</h2>
                    </div>
                    <i class="fa-solid fa-clock-rotate-left fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white border-0 rounded-4 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center py-3 px-4">
                    <div>
                        <div class="small fw-semibold text-white-50 text-uppercase">Absent</div>
                        <h2 class="display-5 fw-bold mb-0">{{ $absentCount }}</h2>
                    </div>
                    <i class="fa-solid fa-xmark-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Attendance Table ===== --}}
    <div class="card shadow-sm border-0 rounded-4 print-ready">
        <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bolder text-dark">
                Attendance Details
                <span class="fw-normal text-muted ms-2" style="font-size:0.85rem;">
                    @if($dateFrom === $dateTo)
                        — {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }}
                    @else
                        — {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                    @endif
                </span>
            </h6>

        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="fw-bolder text-dark text-uppercase">Employee ID</th>
                            <th class="fw-bolder text-dark text-uppercase">Name</th>
                            <th class="fw-bolder text-dark text-uppercase">Department</th>
                            <th class="fw-bolder text-dark text-uppercase">Date</th>
                            <th class="fw-bolder text-dark text-uppercase">Time In</th>
                            <th class="fw-bolder text-dark text-uppercase">Time Out</th>
                            <th class="fw-bolder text-dark text-uppercase">Hours Worked</th>
                            <th class="fw-bolder text-dark text-uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $log)
                            @php
                                $emp = $log->user->employee ?? null;
                            @endphp
                            <tr>
                                <td>{{ $emp ? $emp->employee_id : 'N/A' }}</td>
                                <td class="fw-normal">{{ $log->user->name }}</td>
                                <td>{{ ($emp && $emp->department) ? $emp->department->name : 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                                <td>{{ $log->time_in  ? \Carbon\Carbon::parse($log->time_in)->format('h:i A')  : 'No Log' }}</td>
                                <td>{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : 'No Log' }}</td>
                                <td>
                                    @if($log->time_in && $log->time_out)
                                        @php
                                            $diff = \Carbon\Carbon::parse($log->time_in)->diff(\Carbon\Carbon::parse($log->time_out));
                                        @endphp
                                        {{ $diff->format('%h hr %i min') }}
                                    @else
                                        N/A
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
                                <td colspan="8" class="text-center py-5">
                                    <i class="fa-solid fa-calendar-xmark fa-2x text-muted mb-3 d-block"></i>
                                    <span class="text-muted">No attendance records found for the selected date range.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== Print Styles ===== --}}
    <style>
        @media print {
            #sidebar, .topbar, .btn, .d-print-none { display: none !important; }
            #wrapper { display: block; }
            #content-wrapper { padding: 0 !important; margin: 0 !important; }
            .page-content { padding: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
            .card-header { font-size: 1.2rem; border: none; padding-bottom: 0; }
            .badge-status { border: 1px solid #ccc; padding: 2px 8px; border-radius: 12px; }
        }
    </style>

@endsection