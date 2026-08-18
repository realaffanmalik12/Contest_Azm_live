<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAlert;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class EmergencyBroadcastController extends Controller
{
    public function index()
    {
        $alerts = EmergencyAlert::with('triggeredBy')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.emergency_alerts.index', compact('alerts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alert_type' => 'required|string|max:100',
            'severity' => 'required|in:info,warning,critical',
            'description' => 'required|string',
        ]);

        $alert = EmergencyAlert::create([
            'triggered_by' => auth()->id(),
            'title' => $validated['title'],
            'alert_type' => $validated['alert_type'],
            'severity' => $validated['severity'],
            'description' => $validated['description'],
            'message' => $validated['description'],
            'status' => 'active',
            'triggered_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_EMERGENCY_ALERT',
            'module' => 'Admin - Emergency Broadcast',
            'record_id' => $alert->id,
            'new_values' => json_encode($alert->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.emergency-alerts.index')->with('success', 'Emergency broadcast alert issued successfully!');
    }
}
