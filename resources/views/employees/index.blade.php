@extends('layouts.app')
@section('title', 'Manage Employees')

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

        .employees-table-wrap {
            overflow-x: auto;
        }

        @media (max-width: 767.98px) {
            .employees-toolbar {
                flex-direction: column;
                align-items: stretch !important;
                gap: 12px;
            }

            .employees-toolbar .toolbar-actions {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 8px;
            }

            .employees-toolbar .toolbar-actions .btn {
                width: 100%;
                flex: none;
                text-align: center;
                margin-right: 0 !important;
                min-height: 42px;
            }

            .employees-card-header {
                padding: 12px;
            }

            .employees-table-wrap {
                margin: 0 12px 12px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                background: #fff;
            }

            .employees-table th,
            .employees-table td {
                font-size: 0.93rem;
                padding: 0.8rem 0.55rem;
            }
        }

        @media (max-width: 575.98px) {
            .employees-toolbar .toolbar-actions .btn {
                min-height: 40px;
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }

            .employees-table-wrap {
                margin: 0 10px 10px;
            }

            .employees-card-header {
                padding: 12px 10px;
            }

            .employees-table {
                width: 100%;
                min-width: 0;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 employees-toolbar">
        <h2 class="page-title mb-0">Manage Employees</h2>
        <div class="toolbar-actions">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('employees.archived') }}" class="btn btn-sm me-2 position-relative" style="color: #d97706; border: 1px solid #d97706; border-radius: 8px; padding: 7px 16px; font-weight: 600;">
                    <i class="fa-solid fa-box-archive me-1"></i> View Archived
                    @if(isset($archivedCount) && $archivedCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="background-color: #ef4444 !important;">
                        {{ $archivedCount }}
                    </span>
                    @endif
                </a>
            @endif
            <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Add New Employee</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center employees-card-header">
            
            <form action="{{ route('employees.index') }}" method="GET" class="d-flex employees-search-form">
                <div class="input-group input-group-sm app-search-group">
                    <input type="text" name="search" class="form-control" placeholder="Search name or ID..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-danger"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive employees-table-wrap">
                <table class="table table-hover mb-0 employees-table">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-dark font-weight-bold fw-bolder">
                            <th class="fw-bolder text-dark d-none d-lg-table-cell">ID</th>
                            <th class="fw-bolder text-dark">Name</th>
                            <th class="fw-bolder text-dark d-none d-md-table-cell">Email</th>
                            <th class="fw-bolder text-dark d-none d-md-table-cell">Department</th>
                            <th class="fw-bolder text-dark">Position</th>
                            <th class="fw-bolder text-dark">Status</th>
                            <th class="text-end fw-bolder text-dark">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td class="fw-normal d-none d-lg-table-cell">{{ (int) preg_replace('/[^0-9]/', '', $employee->employee_id) }}</td>
                                <td class="fw-normal">{{ $employee->user->name }}</td>
                                <td class="fw-normal d-none d-md-table-cell">{{ $employee->user->email }}</td>
                                <td class="fw-normal d-none d-md-table-cell">{{ $employee->department ? $employee->department->name : 'N/A' }}</td>
                                <td class="fw-normal">{{ $employee->position }}</td>
                                <td>
                                    @if($employee->status === 'active')
                                        <span class="badge-status badge-present">Active</span>
                                    @elseif($employee->status === 'on_leave')
                                        <span class="badge-status badge-late">On Leave</span>
                                    @else
                                        <span class="badge-status badge-absent">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end employee-actions">
                                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm" title="View Details" style="color: #0891b2; border: 1px solid #0891b2; border-radius: 8px;">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm" title="Edit" style="color: #4f46e5; border: 1px solid #4f46e5; border-radius: 8px;">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('employees.archive', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive this employee? Their status will be set to inactive.')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm" title="Archive" style="color: #d97706; border: 1px solid #d97706; border-radius: 8px;">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No employees found.</td>
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
