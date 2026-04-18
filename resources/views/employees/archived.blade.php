@extends('layouts.app')
@section('title', 'Archived Employees')

@section('content')
    <style>
        .employees-search-form {
            width: 100%;
        }

        .employees-table {
            min-width: 760px;
        }

        .employee-actions {
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .employees-toolbar {
                flex-direction: column;
                align-items: stretch !important;
                gap: 12px;
            }

            .employees-toolbar .btn {
                width: 100%;
            }

            .employees-card-header {
                padding: 12px;
            }

            .employees-table th,
            .employees-table td {
                font-size: 0.93rem;
                padding: 0.8rem 0.55rem;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 employees-toolbar">
        <h2 class="page-title mb-0">Archived Employees</h2>
        <div>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left me-1"></i> Back to Active Lists</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center employees-card-header">
            
            <form action="{{ route('employees.archived') }}" method="GET" class="d-flex employees-search-form">
                <div class="input-group input-group-sm app-search-group">
                    <input type="text" name="search" class="form-control" placeholder="Search name or ID..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('employees.archived') }}" class="btn btn-outline-danger"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 employees-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="d-none d-lg-table-cell">ID</th>
                            <th>Name</th>
                            <th class="d-none d-md-table-cell">Email</th>
                            <th class="d-none d-md-table-cell">Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td class="fw-normal d-none d-lg-table-cell">{{ (int) preg_replace('/[^0-9]/', '', $employee->employee_id) }}</td>
                                <td class="fw-normal">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem; background-color: #64748b;">
                                            {{ substr($employee->user->name, 0, 1) }}
                                        </div>
                                        <div class="ms-2">
                                            {{ $employee->user->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-normal d-none d-md-table-cell">{{ $employee->user->email }}</td>
                                <td class="fw-normal d-none d-md-table-cell">{{ $employee->department ? $employee->department->name : 'N/A' }}</td>
                                <td class="fw-normal">{{ $employee->position }}</td>
                                <td>
                                    <span class="badge-status badge-absent">Archived</span>
                                </td>
                                <td class="text-end employee-actions">
                                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('employees.restore', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to restore this employee back to active status?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Restore Employee">
                                            <i class="fa-solid fa-arrow-rotate-left"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No archived employees found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($employees->hasPages())
        <div class="card-footer bg-white border-top border-light">
            {{ $employees->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
@endsection
