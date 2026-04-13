@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<h2 class="page-title mb-4">My Profile</h2>

<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- Overview Card -->
        <div class="card text-center pb-4 h-100">
            <div class="card-body">
                <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ $user->name === 'System Admin' ? 'A' : substr($user->name, 0, 1) }}
                </div>
                <h4 class="card-title fw-bold">{{ $user->name === 'System Admin' ? 'Admin User' : $user->name }}</h4>
                <p class="text-muted text-capitalize">{{ $user->role }}</p>

                @if($employee && $employee->department)
                    <div class="badge bg-primary px-3 py-2 mt-2">
                        {{ $employee->department->name }}
                    </div>
                @endif
            </div>
            @if($employee)
                <ul class="list-group list-group-flush text-start mt-3">
                    <li class="list-group-item"><strong>Employee ID:</strong> <span class="float-end">{{ $employee->employee_id }}</span></li>
                    <li class="list-group-item"><strong>Position:</strong> <span class="float-end">{{ $employee->position }}</span></li>
                    <li class="list-group-item"><strong>Date Hired:</strong> <span class="float-end">{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}</span></li>
                </ul>
            @endif
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <!-- Details Card -->
        <div class="card h-100">
            <div class="card-header border-bottom">Edit Personal Details</div>
            <div class="card-body p-4">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name === 'System Admin' ? 'Admin User' : $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    @if($employee)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label fw-semibold">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $employee->address) }}">
                        </div>
                    </div>
                    @endif

                    <h5 class="mt-4 mb-3 fw-bold border-bottom pb-2">Change Password</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">New Password <small class="text-muted">(Optional)</small></label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-1"></i> Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
