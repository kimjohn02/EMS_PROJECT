@extends('layouts.app')
@section('title', 'Manage Departments')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0 fw-bolder">Manage Departments</h2>
        <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Add New Department</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <form action="{{ route('departments.index') }}" method="GET" class="d-flex ms-auto">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Search department..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('departments.index') }}" class="btn btn-outline-danger"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-dark font-weight-bold">ID</th>
                            <th class="text-uppercase text-dark font-weight-bold">Department Name</th>
                            <th class="text-uppercase text-dark font-weight-bold">Head / Manager</th>
                            <th class="text-uppercase text-dark font-weight-bold">Total Employees</th>
                            <th class="text-uppercase text-dark font-weight-bold">Status</th>
                            <th class="text-end text-uppercase text-dark font-weight-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td class="text-muted fw-bold">#{{ $department->id }}</td>
                                <td class="fw-semibold">{{ $department->name }}</td>
                                <td>{{ $department->head ?? 'Not Assigned' }}</td>
                                <td>
                                    <span class="fw-bold">{{ $department->employees_count }}</span>
                                </td>
                                <td>
                                    @if($department->is_active)
                                        <span class="badge-status badge-present"><i class="fa-solid fa-check-circle me-1"></i> Active</span>
                                    @else
                                        <span class="badge-status badge-absent"><i class="fa-solid fa-ban me-1"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm" title="Edit" style="color: #4f46e5; border: 1px solid #4f46e5; border-radius: 8px;">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department? Make sure there are no employees assigned to it first.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" title="Delete" style="color: #ef4444; border: 1px solid #ef4444; border-radius: 8px;" {{ $department->employees_count > 0 ? 'disabled' : '' }}>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($departments->hasPages())
        <div class="card-footer bg-white border-top border-light">
            {{ $departments->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
@endsection
