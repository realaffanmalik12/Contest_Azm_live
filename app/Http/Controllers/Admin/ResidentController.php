<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flat;
use App\Models\User;
use App\Models\ResidentProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function create(Flat $flat)
    {
        return view('admin.residents.create', compact('flat'));
    }

    public function store(Request $request, Flat $flat)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|max:30',
        ]);

        DB::transaction(function () use ($validated, $flat, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role' => 'resident',
                'status' => 'active',
            ]);

            $profile = ResidentProfile::create([
                'user_id' => $user->id,
                'flat_id' => $flat->id,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_phone' => $validated['emergency_phone'] ?? null,
                'cnic' => $validated['cnic'] ?? null,
            ]);

            // Update flat status to occupied if vacant
            $flat->update(['status' => 'occupied']);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'ONBOARD_RESIDENT',
                'module' => 'Admin - Residents',
                'record_id' => $user->id,
                'new_values' => json_encode([
                    'user' => $user->toArray(),
                    'profile' => $profile->toArray()
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });

        return redirect()->route('admin.flats.index')->with('success', 'Resident onboarded successfully.');
    }

    public function offboard(Request $request, User $user)
    {
        if ($user->role !== 'resident') {
            return back()->with('error', 'User is not a resident.');
        }

        $oldStatus = $user->status;
        $user->update(['status' => 'inactive']);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'OFFBOARD_RESIDENT',
            'module' => 'Admin - Residents',
            'record_id' => $user->id,
            'old_values' => json_encode(['status' => $oldStatus]),
            'new_values' => json_encode(['status' => 'inactive']),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Resident has been offboarded (deactivated).');
    }
}
