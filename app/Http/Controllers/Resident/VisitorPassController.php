<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\ResidentProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitorPassController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            $visitors = collect();
            return view('resident.visitor_passes.index', compact('visitors'))
                ->with('warning', 'Your resident profile is not set up yet. Please contact the Admin to link your account to a flat.');
        }

        $visitors = Visitor::where('resident_id', $profile->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('resident.visitor_passes.index', compact('visitors'));
    }

    public function create()
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return redirect()->route('resident.visitor-passes.index')
                ->with('warning', 'Your resident profile is not set up yet. Please contact the Admin to link your account to a flat before generating gate passes.');
        }

        return view('resident.visitor_passes.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return redirect()->route('resident.visitor-passes.index')
                ->with('error', 'Your resident profile is not set up. Ask the Admin to onboard your flat first.');
        }

        $validated = $request->validate([
            'visitor_name'   => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
            'visitor_type'   => 'nullable|in:guest,delivery,cab,vendor',
            'valid_until'    => 'required|date|after:now',
        ]);

        // Generate 6-digit numeric gate code AND a cryptographically unique QR token
        $gateCode = rand(100000, 999999);
        $qrToken  = Str::uuid()->toString();

        $visitor = Visitor::create([
            'resident_id'    => $profile->id,
            'flat_id'        => $profile->flat_id,
            'visitor_name'   => $validated['visitor_name'],
            'phone'          => $validated['phone'] ?? null,
            'vehicle_number' => isset($validated['vehicle_number']) ? strtoupper($validated['vehicle_number']) : null,
            'visitor_type'   => $validated['visitor_type'] ?? 'guest',
            'gate_pass_code' => (string) $gateCode,
            'qr_token'       => $qrToken,
            'valid_from'     => now(),
            'valid_until'    => $validated['valid_until'],
            'status'         => 'active',
        ]);

        AuditLog::create([
            'user_id'    => $user->id,
            'action'     => 'CREATE_VISITOR_PASS',
            'module'     => 'Resident - Visitor Pass',
            'record_id'  => $visitor->id,
            'new_values' => json_encode($visitor->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('resident.visitor-passes.show', $visitor->id)
            ->with('success', "Digital Gate Pass #{$gateCode} generated successfully!");
    }

    public function show($id)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        $visitor = Visitor::findOrFail($id);

        // Authorization check — only the owning resident can see their own pass
        if ($profile && $visitor->resident_id !== $profile->id) {
            abort(403, 'Unauthorized access to this gate pass.');
        }

        return view('resident.visitor_passes.show', compact('visitor'));
    }
}
