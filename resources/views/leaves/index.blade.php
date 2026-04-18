@extends('layouts.app')
@section('title', 'Leave Requests')

@section('content')
    <style>
        .leaves-toolbar {
            gap: 12px;
        }

        .leaves-filter-form {
            width: 100%;
            justify-content: flex-end;
        }

        .leaves-filter-select {
            width: auto;
            height: 50px;
            font-size: 1.1rem;
        }

        .leaves-table {
            min-width: 760px;
        }

        .leave-reason-cell {
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .leave-actions {
            white-space: nowrap;
        }

        .leave-view-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            text-decoration: none;
        }

        .leave-view-btn i {
            font-size: 0.95rem;
        }

        .leave-status-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: default;
            user-select: none;
        }

        .leave-status-btn.approved {
            color: #166534;
            background-color: #dcfce7;
            border-color: #86efac;
        }

        .leave-status-btn.pending {
            color: #92400e;
            background-color: #fef9c3;
            border-color: #fde68a;
        }

        .leave-action-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: nowrap;
        }

        @media (max-width: 767.98px) {
            .leaves-toolbar {
                flex-direction: column;
                align-items: stretch !important;
            }

            .leaves-filter-form {
                justify-content: stretch;
                align-items: stretch !important;
                width: 100%;
            }

            .leaves-filter-form .app-search-group {
                max-width: 100%;
            }

            .leaves-filter-select {
                width: 100%;
                font-size: 1rem;
                height: 44px;
            }

            .leaves-table th,
            .leaves-table td {
                font-size: 0.93rem;
                padding: 0.8rem 0.55rem;
            }

            .leave-reason-cell {
                max-width: 140px;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 leaves-toolbar">
        <h2 class="page-title mb-0 fw-bolder">Manage Leaves</h2>
        @if(auth()->user()->role === 'employee')
        <a href="{{ route('leaves.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Apply for Leave</a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center py-3">
            <form action="{{ route('leaves.index') }}" method="GET" class="d-flex ms-auto flex-wrap gap-2 align-items-center leaves-filter-form">
                <select name="status" class="form-select leaves-filter-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                
                @if(auth()->user()->role !== 'employee')
                <div class="input-group app-search-group">
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
                <table class="table table-hover mb-0 leaves-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-dark font-weight-bold d-none d-lg-table-cell">ID</th>
                            @if(auth()->user()->role !== 'employee')
                            <th class="text-uppercase text-dark font-weight-bold d-none d-md-table-cell">Employee Name</th>
                            @endif
                            <th class="text-uppercase text-dark font-weight-bold d-none d-sm-table-cell">Type</th>
                            <th class="text-uppercase text-dark font-weight-bold d-none d-lg-table-cell">Start Date</th>
                            <th class="text-uppercase text-dark font-weight-bold d-none d-lg-table-cell">End Date</th>
                            <th class="text-uppercase text-dark font-weight-bold">Reason</th>
                            <th class="text-uppercase text-dark font-weight-bold">Status</th>
                            <th class="text-end text-uppercase text-dark font-weight-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td class="fw-normal d-none d-lg-table-cell">{{ $leave->id }}</td>
                                @if(auth()->user()->role !== 'employee')
                                <td class="fw-normal d-none d-md-table-cell">{{ $leave->user->name }}</td>
                                @endif
                                <td class="fw-normal d-none d-sm-table-cell"><span class="text-capitalize">{{ $leave->type }}</span></td>
                                <td class="fw-normal d-none d-lg-table-cell">{{ $leave->start_date->format('M d, Y') }}</td>
                                <td class="fw-normal d-none d-lg-table-cell">{{ $leave->end_date->format('M d, Y') }}</td>
                                <td class="fw-normal leave-reason-cell" title="{{ $leave->reason }}">
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
                                <td class="text-end leave-actions">
                                    @if(auth()->user()->role === 'employee')
                                        @if($leave->status === 'approved')
                                            <span class="leave-status-btn approved"><i class="fa-solid fa-check"></i> Approved</span>
                                        @elseif($leave->status === 'rejected')
                                            <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm leave-view-btn" title="View Rejection Reason" style="color: #2563eb; border: 1px solid #2563eb; background-color: #eff6ff;">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        @else
                                            <span class="leave-status-btn pending"><i class="fa-regular fa-clock"></i> Pending</span>
                                        @endif
                                    @else
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm leave-view-btn" title="View Details" style="color: #2563eb; border: 1px solid #2563eb; background-color: #eff6ff;">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role !== 'employee' ? 8 : 7 }}" class="text-center py-4 text-muted">No leave requests found.</td>
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
