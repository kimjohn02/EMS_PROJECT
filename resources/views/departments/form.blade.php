@extends('layouts.app')
@section('title', isset($department) ? 'Edit Department' : 'Add Department')

@section('content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">{{ isset($department) ? 'Edit' : 'Add' }} Department</h2>
                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" method="POST">
                        @csrf
                        @if(isset($department))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', isset($department) ? $department->name : '') }}" required placeholder="e.g. Finance, IT, HR">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Department Head / Manager</label>
                            <input type="text" name="head" class="form-control" value="{{ old('head', isset($department) ? $department->head : '') }}" placeholder="Name of Manager">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Brief details about this department...">{{ old('description', isset($department) ? $department->description : '') }}</textarea>
                        </div>
                        
                        @if(isset($department))
                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch" name="is_active" value="1" {{ $department->is_active ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 text-muted fw-semibold" style="font-size: 0.85rem;" for="statusSwitch">Department is Active</label>
                        </div>
                        @endif

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
                            <button type="submit" class="btn btn-primary" style="font-weight: 600;">
                                <i class="fa-solid fa-save me-2"></i> {{ isset($department) ? 'Update Department' : 'Save Department' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
