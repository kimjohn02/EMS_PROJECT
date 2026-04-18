@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h2 class="page-title mb-0"> Attendance Report</h2>
        <button type="button" class="btn btn-secondary" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i> Print Report
        </button>
    </div>

    <!-- Header for Printing -->
    <div class="d-none d-print-block text-center mb-4">
        <h2>EMS System</h2>
        <h4>Attendance Report for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h4>
        <hr>
    </div>

    <!-- Filter controls -->
    <div class="card d-print-none mb-4">
        <div class="card-body">
            <form action="{{ route('reports.index') }}" method="GET" class="d-flex align-items-end gap-3">
                <div>
                    <label for="date" class="form-label fw-semibold">Select Date</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ $date }}" required>
                </div>
                <button type="submit" class="btn btn-primary px-4">Generate Report</button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-check-circle me-2"></i>Present</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $presentCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-clock-rotate-left me-2"></i>Late</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $lateCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-xmark-circle me-2"></i>Absent</h5>
                    <h2 class="display-5 fw-bold mb-0">{{ $absentCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Data -->
    <!-- Detailed Data -->

    <div class="card print-ready">
        <div class="card-header">
            Attendance Details for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-dark font-weight-bold fw-bolder">
                            <th class="fw-bolder text-dark">Employee ID</th>
                            <th class="fw-bolder text-dark">Name</th>
                            <th class="fw-bolder text-dark">Department</th>
                            <th class="fw-bolder text-dark">Time In</th>
                            <th class="fw-bolder text-dark">Time Out</th>
                            <th class="fw-bolder text-dark">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            @php
                                $emp = $attendance->user->employee ?? null;
                            @endphp
                            <tr>
                                <td>{{ $emp ? $emp->employee_id : 'N/A' }}</td>
                                <td class="fw-normal">{{ $attendance->user->name }}</td>
                                <td>{{ $emp && $emp->department ? $emp->department->name : 'N/A' }}</td>
                                <td>{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '--' }}
                                </td>
                                <td>{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '--' }}
                                </td>
                                <td>
                                    <span class="badge badge-{{ $attendance->status }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No records found for the selected date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {

            #sidebar,
            .topbar,
            .btn {
                display: none !important;
            }

            #wrapper {
                display: block;
            }

            #content-wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }

            .page-content {
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-header {
                font-size: 1.5rem;
                border: none;
                padding-bottom: 0;
            }
        }
    </style>
@endsection