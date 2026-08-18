<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceBill;
use App\Models\Payment;
use App\Models\ResidentProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            $bills = collect();
            return view('resident.bills.index', compact('bills'));
        }

        $bills = MaintenanceBill::where('flat_id', $profile->flat_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('resident.bills.index', compact('bills'));
    }

    public function show(MaintenanceBill $bill)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        if ($bill->flat_id !== $profile->flat_id) {
            abort(403, 'Unauthorized access to bill details.');
        }

        return view('resident.bills.show', compact('bill'));
    }

    public function pay(Request $request, MaintenanceBill $bill)
    {
        $user = auth()->user();
        $profile = ResidentProfile::where('user_id', $user->id)->firstOrFail();

        if ($bill->flat_id !== $profile->flat_id) {
            abort(403);
        }

        if ($bill->payment_status === 'paid') {
            return back()->with('error', 'This bill has already been paid.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:Card,Bank Transfer,UPI,Cash',
        ]);

        $transactionId = 'SIM-PAY-' . strtoupper(Str::random(10));

        // Create Payment record
        $payment = Payment::create([
            'bill_id' => $bill->id,
            'resident_id' => $profile->id,
            'amount_paid' => $bill->amount_due,
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $transactionId,
            'payment_date' => now(),
            'status' => 'completed',
        ]);

        // Flip bill status
        $bill->update(['payment_status' => 'paid']);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'SIMULATED_BILL_PAYMENT',
            'module' => 'Resident - Billing',
            'record_id' => $payment->id,
            'new_values' => json_encode($payment->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('resident.bills.show', $bill)->with('success', 'Simulated payment processed successfully! Transaction ID: ' . $transactionId);
    }
}
