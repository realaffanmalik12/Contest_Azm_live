<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class WorkTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Complaint::with(['residentProfile.user', 'flat'])
            ->where('assigned_staff_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        $pendingCount = Complaint::where('assigned_staff_id', $user->id)->where('status', 'pending')->count();
        $inProgressCount = Complaint::where('assigned_staff_id', $user->id)->where('status', 'in_progress')->count();
        $resolvedCount = Complaint::where('assigned_staff_id', $user->id)->where('status', 'resolved')->count();

        return view('maintenance.dashboard', compact('tickets', 'pendingCount', 'inProgressCount', 'resolvedCount'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $user = auth()->user();

        if ($complaint->assigned_staff_id !== $user->id) {
            abort(403, 'Unauthorized: This ticket is not assigned to you.');
        }

        $validated = $request->validate([
            'status' => 'required|in:in_progress,resolved',
            'completion_notes' => 'nullable|string',
        ]);

        $oldValues = $complaint->toArray();

        $complaint->update([
            'status' => $validated['status'],
            'description' => $validated['completion_notes'] 
                ? $complaint->description . "\n\n[Staff Resolution Note]: " . $validated['completion_notes'] 
                : $complaint->description,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'UPDATE_TICKET_STATUS',
            'module' => 'Maintenance - Staff',
            'record_id' => $complaint->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($complaint->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', "Ticket status updated to " . ucfirst(str_replace('_', ' ', $validated['status'])));
    }
}
