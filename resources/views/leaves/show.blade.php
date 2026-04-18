@extends('layouts.app')
@section('title', 'Leave Details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to Leaves</a>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-6 col-xl-5">
            @if((auth()->user()->isAdmin() || auth()->user()->isHR()) && $leave->status === 'pending')
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary font-weight-bold">Review Request</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-check me-1"></i> Approve Request</button>
                        </form>

                        <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Rejection Reason</label>
                                <textarea name="rejection_reason" rows="4" class="form-control @error('rejection_reason') is-invalid @enderror" placeholder="Write the reason for rejecting this leave request..." required>{{ old('rejection_reason') }}</textarea>
                                @error('rejection_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-danger w-100"><i class="fa-solid fa-xmark me-1"></i> Reject Request</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="m-0 text-primary font-weight-bold">Status Information</h5>
                    </div>
                    <div class="card-body">
                        @if($leave->status === 'approved')
                            <div class="alert alert-success mb-0">
                                This leave request has been approved.
                            </div>
                        @elseif($leave->status === 'rejected')
                            <div class="alert alert-danger mb-0">
                                This leave request has been rejected.
                                @if($leave->rejection_reason)
                                    <hr>
                                    <strong>Reason:</strong> {{ $leave->rejection_reason }}
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                This leave request is still pending review.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection