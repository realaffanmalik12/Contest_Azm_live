<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\GateLog;
use App\Models\Flat;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class GateManagementController extends Controller
{
    public function dashboard()
    {
        $activeEntriesCount = GateLog::whereNull('exit_time')->count();
        $todayLogsCount = GateLog::whereDate('entry_time', today())->count();
        $flats = Flat::orderBy('block_name')->orderBy('flat_number')->get();

        // Query Overstay Alerts: GateLog entries with no exit timestamp open for more than 4 hours
        $overstayLogs = GateLog::with(['visitor', 'flat'])
            ->whereNull('exit_time')
            ->where('entry_time', '<=', now()->subHours(4))
            ->orderBy('entry_time', 'asc')
            ->get();

        $recentLogs = GateLog::with(['visitor', 'flat', 'guard'])
            ->orderBy('entry_time', 'desc')
            ->take(15)
            ->get();

        return view('guard.dashboard', compact('activeEntriesCount', 'todayLogsCount', 'flats', 'overstayLogs', 'recentLogs'));
    }

    public function verifyPass(Request $request)
    {
        $validated = $request->validate([
            'gate_pass_code' => 'required|string',
        ]);

        $code = trim($validated['gate_pass_code']);

        // Fast lookup indexed query
        $visitor = Visitor::with('flat')
            ->where('gate_pass_code', $code)
            ->first();

        if (!$visitor) {
            return back()->with('error', 'INVALID PASS: No matching visitor gate pass code found.');
        }

        if (now()->greaterThan($visitor->valid_until)) {
            return back()->with('error', "EXPIRED PASS: Gate pass code {$code} expired at " . $visitor->valid_until);
        }

        // Check if already checked in and inside premises
        $existingLog = GateLog::where('visitor_id', $visitor->id)
            ->whereNull('exit_time')
            ->first();

        if ($existingLog) {
            return back()->with('info', "VISITOR INSIDE: Visitor {$visitor->visitor_name} checked in at {$existingLog->entry_time}. Use Checkout button to record exit.");
        }

        // Create GateLog Entry
        $gateLog = GateLog::create([
            'visitor_id' => $visitor->id,
            'flat_id' => $visitor->flat_id,
            'guard_id' => auth()->id(),
            'entry_time' => now(),
            'vehicle_number' => $visitor->vehicle_number,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'VERIFY_GATE_PASS_ENTRY',
            'module' => 'Guard - Gate',
            'record_id' => $gateLog->id,
            'new_values' => json_encode($gateLog->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('guard.dashboard')->with('success', "ACCESS GRANTED: Gate Pass Verified for {$visitor->visitor_name} (Flat {$visitor->flat->block_name}-{$visitor->flat->flat_number}). Entry Logged.");
    }

    public function logWalkIn(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'flat_id' => 'required|exists:flats,id',
            'vehicle_number' => 'nullable|string|max:50',
            'purpose' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('visitors', 'public');
        }

        // Create Visitor Record for Walk-in
        $visitor = Visitor::create([
            'flat_id' => $validated['flat_id'],
            'visitor_name' => $validated['visitor_name'],
            'phone' => $validated['phone'],
            'vehicle_number' => $validated['vehicle_number'] ? strtoupper($validated['vehicle_number']) : null,
            'purpose' => $validated['purpose'] ?? 'Walk-in Guest',
            'gate_pass_code' => (string) rand(100000, 999999),
            'valid_until' => now()->addHours(12),
            'status' => 'approved',
        ]);

        // Create GateLog Entry immediately
        $gateLog = GateLog::create([
            'visitor_id' => $visitor->id,
            'flat_id' => $validated['flat_id'],
            'guard_id' => auth()->id(),
            'entry_time' => now(),
            'vehicle_number' => $visitor->vehicle_number,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'LOG_WALKIN_VISITOR',
            'module' => 'Guard - Gate',
            'record_id' => $gateLog->id,
            'new_values' => json_encode($gateLog->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('guard.dashboard')->with('success', "WALK-IN LOGGED: Entry recorded for {$visitor->visitor_name}.");
    }

    public function checkout(Request $request, GateLog $gateLog)
    {
        if ($gateLog->exit_time) {
            return back()->with('error', 'Visitor has already checked out.');
        }

        $gateLog->update(['exit_time' => now()]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CHECKOUT_VISITOR',
            'module' => 'Guard - Gate',
            'record_id' => $gateLog->id,
            'new_values' => json_encode(['exit_time' => now()]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('guard.dashboard')->with('success', "CHECKOUT LOGGED: Visitor exit recorded successfully.");
    }
}
