@extends('layouts.app')
@section('title', 'Leave Requests')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0 fw-bolder">Manage Leaves</h2>
        @if(auth()->user()->role === 'employee')
        <a href="{{ route('leaves.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Apply for Leave</a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center py-3">
            <form action="{{ route('leaves.index') }}" method="GET" class="d-flex ms-auto flex-wrap gap-2 align-items-center">
                <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                
                @if(auth()->user()->role !== 'employee')
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Search employee..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            @if(auth()->user()->role !== 'employee')
                            <th class="text-uppercase text-dark font-weight-bold">Employee</th>
                            @endif
                            <th class="text-uppercase text-dark font-weight-bold">Type</th>
                            <th class="text-uppercase text-dark font-weight-bold">Start Date</th>
                            <th class="text-uppercase text-dark font-weight-bold">End Date</th>
                            <th class="text-uppercase text-dark font-weight-bold">Reason</th>
                            <th class="text-uppercase text-dark font-weight-bold">Status</th>
                            <th class="text-end text-uppercase text-dark font-weight-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                @if(auth()->user()->role !== 'employee')
                                <td class="fw-bold">{{ $leave->user->name }}</td>
                                @endif
                                <td><span class="text-capitalize">{{ $leave->type }}</span></td>
                                <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                <td style="max-width: 200px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $leave->reason }}">
                                    {{ $leave->reason }}
                                </td>
                                <td>
                                    @if($leave->status === 'approved')
                                        <span class="badge-status badge-present">Approved</span>
                                    @elseif($leave->status === 'rejected')
                                        <span class="badge-status badge-absent">Rejected</span>
                                    @else
                                        <span class="badge-status badge-late">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if(auth()->user()->role !== 'employee')
                                        @if($leave->status === 'pending')
                                        <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm rounded-3 me-2" title="Approve" style="background-color: #ecfdf5; color: #059669; border: 1px solid #10b981;"><i class="fa-solid fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm rounded-3" title="Reject" style="background-color: #fef2f2; color: #dc2626; border: 1px solid #ef4444;"><i class="fa-solid fa-xmark"></i></button>
                                        </form>
                                        @else
                                            <span class="text-muted"><i class="fa-solid fa-lock" style="color: #94a3b8;"></i></span>
                                        @endif
                                    @else
                                        @if($leave->status === 'pending' && $leave->user_id === auth()->id())
                                        <form action="{{ route('leaves.cancel', $leave->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this leave request?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel Request"><i class="fa-solid fa-ban me-1"></i>Cancel</button>
                                        </form>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role !== 'employee' ? 7 : 6 }}" class="text-center py-4 text-muted">No leave requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($leaves->hasPages())
        <div class="card-footer bg-white border-top border-light">
            {{ $leaves->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
@endsection
