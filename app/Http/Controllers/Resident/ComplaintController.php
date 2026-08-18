<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ResidentProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            $complaints = collect();
            return view('resident.complaints.index', compact('complaints'));
        }

        $complaints = Complaint::where('resident_id', $profile->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('resident.complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('resident.complaints.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'category' => 'required|in:Plumbing,Electrical,Elevator,Security,General',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('complaints', 'public');
        }

        $complaint = Complaint::create([
            'resident_id' => $profile->id,
            'flat_id' => $profile->flat_id,
            'category' => $validated['category'],
            'description' => $validated['description'],
            'photo_url' => $photoPath,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'RAISE_COMPLAINT',
            'module' => 'Resident - Complaints',
            'record_id' => $complaint->id,
            'new_values' => json_encode($complaint->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('resident.complaints.index')->with('success', 'Complaint ticket raised successfully.');
    }
}
