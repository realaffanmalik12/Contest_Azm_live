<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class HelpdeskController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['residentProfile.user', 'assignedStaff', 'flat']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);
        $maintenanceStaff = User::where('role', 'maintenance')->get();

        return view('admin.complaints.index', compact('complaints', 'maintenanceStaff'));
    }

    public function assignStaff(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'assigned_staff_id' => 'required|exists:users,id',
        ]);

        $oldValues = $complaint->toArray();
        $complaint->update([
            'assigned_staff_id' => $validated['assigned_staff_id'],
            'status' => 'in_progress',
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'ASSIGN_COMPLAINT',
            'module' => 'Admin - Helpdesk',
            'record_id' => $complaint->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($complaint->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Complaint assigned to maintenance staff.');
    }
}
