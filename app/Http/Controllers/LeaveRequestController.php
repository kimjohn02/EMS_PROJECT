<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = LeaveRequest::with('user')->orderBy('created_at', 'desc');

        // HR & Admin see all leaves, employees only see theirs
        if (!($user->isAdmin() || $user->isHR())) {
            $query->where('user_id', $user->id);
        }

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply search filter (Employee name) for Admin/HR only
        if ($request->filled('search') && ($user->isAdmin() || $user->isHR())) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $leaves = $query->paginate(10)->withQueryString();

        return view('leaves.index', compact('leaves'));
    }

    public function show(LeaveRequest $leave)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!($user->isAdmin() || $user->isHR()) && $leave->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $leave->load('user');

        return view('leaves.show', compact('leave'));
    }

    public function create()
    {
        return view('leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:vacation,sick,emergency,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    public function updateStatus(Request $request, LeaveRequest $leave)
    {
        // Only Admin or HR can update status
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isHR()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|required_if:status,rejected|string|max:1000',
        ]);

        $leave->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['status'] === 'rejected' ? ($validated['rejection_reason'] ?? null) : null,
        ]);

        return back()->with('success', 'Leave request status updated.');
    }

    public function cancel(LeaveRequest $leave)
    {
        // Only the owner can cancel, and only if still pending
        if ($leave->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $leave->delete();

        return back()->with('success', 'Leave request cancelled successfully.');
    }
}
