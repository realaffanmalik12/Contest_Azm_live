<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\ResidentProfile;
use App\Models\FamilyMember;
use App\Models\Vehicle;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = ResidentProfile::with(['flat', 'familyMembers', 'vehicles'])
            ->where('user_id', $user->id)
            ->first();

        return view('resident.profile.index', compact('user', 'profile'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|max:30',
        ]);

        $user->update(['phone' => $validated['phone']]);

        if ($profile) {
            $profile->update([
                'emergency_contact' => $validated['emergency_contact'],
                'emergency_phone' => $validated['emergency_phone'],
                'cnic' => $validated['cnic'],
            ]);
        }

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'UPDATE_RESIDENT_PROFILE',
            'module' => 'Resident - Profile',
            'record_id' => $user->id,
            'new_values' => json_encode($validated),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Profile details updated successfully.');
    }

    public function storeFamilyMember(Request $request)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
        ]);

        $family = FamilyMember::create([
            'resident_profile_id' => $profile->id,
            'name' => $validated['name'],
            'relationship' => $validated['relationship'],
            'phone' => $validated['phone'],
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'ADD_FAMILY_MEMBER',
            'module' => 'Resident - Profile',
            'record_id' => $family->id,
            'new_values' => json_encode($family->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Family member added successfully.');
    }

    public function destroyFamilyMember(Request $request, FamilyMember $familyMember)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        if ($familyMember->resident_profile_id !== $profile->id) {
            abort(403);
        }

        $familyMember->delete();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'DELETE_FAMILY_MEMBER',
            'module' => 'Resident - Profile',
            'record_id' => $familyMember->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Family member removed.');
    }

    public function storeVehicle(Request $request)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:50',
            'vehicle_type' => 'required|in:Car,Bike,SUV,Other',
            'parking_slot' => 'nullable|string|max:50',
        ]);

        $vehicle = Vehicle::create([
            'resident_id' => $profile->id,
            'vehicle_number' => strtoupper($validated['vehicle_number']),
            'vehicle_type' => $validated['vehicle_type'],
            'parking_slot' => $validated['parking_slot'],
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'ADD_VEHICLE',
            'module' => 'Resident - Profile',
            'record_id' => $vehicle->id,
            'new_values' => json_encode($vehicle->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Vehicle registered successfully.');
    }

    public function destroyVehicle(Request $request, Vehicle $vehicle)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        if ($vehicle->resident_id !== $profile->id) {
            abort(403);
        }

        $vehicle->delete();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'DELETE_VEHICLE',
            'module' => 'Resident - Profile',
            'record_id' => $vehicle->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Vehicle record removed.');
    }
}
