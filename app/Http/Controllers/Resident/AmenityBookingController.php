<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityBooking;
use App\Models\ResidentProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AmenityBookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        $amenities = Amenity::all();
        $myBookings = $profile ? AmenityBooking::with('amenity')->where('resident_id', $profile->id)->orderBy('created_at', 'desc')->paginate(10) : collect();

        return view('resident.amenity_bookings.index', compact('amenities', 'myBookings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'amenity_id' => 'required|exists:amenities,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Prevent Double Booking Validation
        $existingBooking = AmenityBooking::where('amenity_id', $validated['amenity_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })
            ->exists();

        if ($existingBooking) {
            return back()->with('error', 'This amenity is already booked for the selected date and time slot.');
        }

        $amenity = Amenity::findOrFail($validated['amenity_id']);

        $booking = AmenityBooking::create([
            'amenity_id' => $amenity->id,
            'resident_id' => $profile->id,
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'confirmed',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'BOOK_AMENITY',
            'module' => 'Resident - Amenity Booking',
            'record_id' => $booking->id,
            'new_values' => json_encode($booking->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('resident.amenity-bookings.index')->with('success', 'Amenity booked successfully!');
    }
}
