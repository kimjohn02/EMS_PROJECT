@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0 fw-bolder">User Management</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add New User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Search name or email..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger"><i
                                class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="fw-bolder text-dark">Name</th>
                            <th class="fw-bolder text-dark">Email</th>
                            <th class="fw-bolder text-dark">Role</th>
                            <th class="fw-bolder text-dark">Status</th>
                            <th class="fw-bolder text-dark">Created At</th>
                            <th class="text-end fw-bolder text-dark">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                </td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge badge-admin">Admin</span>
                                    @elseif($user->role === 'hr')
                                        <span class="badge"
                                            style="background-color: #e0e7ff; color: #4338ca; font-weight: 600; border-radius: 20px; padding: 4px 12px; font-size: 0.78rem;">HR</span>
                                    @else
                                        <span class="badge badge-present">Employee</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active ?? true)
                                        <span class="badge-status badge-present">Active</span>
                                    @else
                                        <span class="badge-status badge-absent">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm" title="Edit"
                                        style="color: #4f46e5; border: 1px solid #4f46e5; border-radius: 8px;">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" title="Delete"
                                                style="color: #ef4444; border: 1px solid #ef4444; border-radius: 8px;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-users-slash mb-2 d-block" style="font-size: 2rem; opacity: 0.3;"></i>
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white border-top border-light">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection