@extends('layouts.app')
@section('title', isset($employee) ? 'Edit Employee' : 'Add Employee')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">{{ isset($employee) ? 'Edit' : 'Add' }} Employee</h2>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST">
                        @csrf
                        @if(isset($employee))
                            @method('PUT')
                        @endif

                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Account Information</h6>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', isset($employee) ? $employee->user->name : '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', isset($employee) ? $employee->user->email : '') }}" required>
                            </div>
                        </div>

                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Employment Details</h6>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Department <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-select" required>
                                    <option value="">Select Department...</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ (old('department_id', isset($employee) ? $employee->department_id : '') == $dept->id) ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Position <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control" value="{{ old('position', isset($employee) ? $employee->position : '') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Date Hired <span class="text-danger">*</span></label>
                                <input type="date" name="date_hired" class="form-control" value="{{ old('date_hired', isset($employee) ? $employee->date_hired->format('Y-m-d') : '') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', isset($employee) ? $employee->phone : '') }}">
                            </div>

                            @if(isset($employee))
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $employee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="on_leave" {{ $employee->status === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                </select>
                            </div>
                            @endif
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger" style="border-radius: 8px; font-size: 0.85rem;">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" style="font-size: 1rem; font-weight: 600;">
                                <i class="fa-solid fa-save me-2"></i> {{ isset($employee) ? 'Save Changes' : 'Create Employee' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if(!isset($employee))
            <div class="mt-3 text-center text-muted" style="font-size: 0.85rem;">
                <i class="fa-solid fa-circle-info me-1"></i> A default password ("password") will be generated for new employees.
            </div>
            @endif
        </div>
    </div>
@endsection
