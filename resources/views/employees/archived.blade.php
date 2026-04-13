@extends('layouts.app')
@section('title', 'Archived Employees')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">Archived Employees</h2>
        <div>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left me-1"></i> Back to Active Lists</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            
            <form action="{{ route('employees.archived') }}" method="GET" class="d-flex">
                <div class="input-group input-group-sm" style="width: 250px;">
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
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>EMP ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td class="fw-bold text-muted">{{ $employee->employee_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem; background-color: #64748b;">
                                            {{ substr($employee->user->name, 0, 1) }}
                                        </div>
                                        <div class="ms-2">
                                            <div class="fw-semibold">{{ $employee->user->name }}</div>
                                            <div class="text-muted" style="font-size: 0.8rem;">{{ $employee->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->department ? $employee->department->name : 'N/A' }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>
                                    <span class="badge-status badge-absent">Archived</span>
                                </td>
                                <td class="text-end">
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
                                <td colspan="6" class="text-center py-4 text-muted">No archived employees found.</td>
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
