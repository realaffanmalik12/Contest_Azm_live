<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flat;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class FlatController extends Controller
{
    public function index(Request $request)
    {
        $query = Flat::with(['residentProfiles.user']);

        if ($request->filled('block')) {
            $query->where('block_name', 'like', '%' . $request->block . '%');
        }

        if ($request->filled('flat_number')) {
            $query->where('flat_number', 'like', '%' . $request->flat_number . '%');
        }

        if ($request->filled('occupancy_type')) {
            $query->where('occupancy_type', $request->occupancy_type);
        }

        $flats = $query->orderBy('block_name')->orderBy('flat_number')->paginate(15);

        return view('admin.flats.index', compact('flats'));
    }

    public function create()
    {
        return view('admin.flats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'block_name' => 'required|string|max:50',
            'flat_number' => 'required|string|max:20',
            'floor' => 'nullable|integer',
            'occupancy_type' => 'required|in:Owner,Tenant,Vacant',
            'status' => 'required|in:occupied,vacant,maintenance',
        ]);

        $flat = Flat::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_FLAT',
            'module' => 'Admin - Flats',
            'record_id' => $flat->id,
            'new_values' => json_encode($flat->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.flats.index')->with('success', 'Flat created successfully.');
    }

    public function edit(Flat $flat)
    {
        return view('admin.flats.edit', compact('flat'));
    }

    public function update(Request $request, Flat $flat)
    {
        $validated = $request->validate([
            'block_name' => 'required|string|max:50',
            'flat_number' => 'required|string|max:20',
            'floor' => 'nullable|integer',
            'occupancy_type' => 'required|in:Owner,Tenant,Vacant',
            'status' => 'required|in:occupied,vacant,maintenance',
        ]);

        $oldValues = $flat->toArray();
        $flat->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_FLAT',
            'module' => 'Admin - Flats',
            'record_id' => $flat->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($flat->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.flats.index')->with('success', 'Flat updated successfully.');
    }

    public function destroy(Request $request, Flat $flat)
    {
        $oldValues = $flat->toArray();
        $flat->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_FLAT',
            'module' => 'Admin - Flats',
            'record_id' => $flat->id,
            'old_values' => json_encode($oldValues),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.flats.index')->with('success', 'Flat deleted successfully.');
    }
}
