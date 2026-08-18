<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flat;
use App\Models\MaintenanceBill;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceBill::with('flat');

        if ($request->filled('billing_month')) {
            $query->where('billing_month', $request->billing_month);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bills = $query->orderBy('created_at', 'desc')->paginate(15);

        // Check and apply penalties for past due date unpaid bills dynamically
        foreach ($bills as $bill) {
            if (now()->greaterThan($bill->due_date) && $bill->payment_status === 'unpaid' && $bill->penalty == 0) {
                // Apply penalty ($10 or 5%)
                $penalty = 25.00;
                $bill->update([
                    'penalty' => $penalty,
                    'amount_due' => $bill->amount_due + $penalty,
                    'payment_status' => 'overdue',
                ]);
            }
        }

        // Summary collection report stats
        $totalCollected = MaintenanceBill::where('payment_status', 'paid')->sum('amount_due');
        $totalOutstanding = MaintenanceBill::whereIn('payment_status', ['unpaid', 'overdue', 'partially_paid'])->sum('amount_due');

        return view('admin.bills.index', compact('bills', 'totalCollected', 'totalOutstanding'));
    }

    public function create()
    {
        $flats = Flat::all();
        return view('admin.bills.create', compact('flats'));
    }

    public function generateBulk(Request $request)
    {
        $validated = $request->validate([
            'billing_month' => 'required|date',
            'due_date' => 'required|date|after:billing_month',
            'water_charges' => 'required|numeric|min:0',
            'security_charges' => 'required|numeric|min:0',
            'repair_charges' => 'required|numeric|min:0',
            'other_charges' => 'nullable|numeric|min:0',
        ]);

        $other = $validated['other_charges'] ?? 0;
        $totalPerFlat = $validated['water_charges'] + $validated['security_charges'] + $validated['repair_charges'] + $other;

        $flats = Flat::where('status', 'occupied')->get();
        $generatedCount = 0;

        foreach ($flats as $flat) {
            $billNumber = 'BILL-' . strtoupper(Str::random(8));

            $bill = MaintenanceBill::create([
                'flat_id' => $flat->id,
                'bill_number' => $billNumber,
                'billing_month' => $validated['billing_month'],
                'amount_due' => $totalPerFlat,
                'water_charges' => $validated['water_charges'],
                'security_charges' => $validated['security_charges'],
                'repair_charges' => $validated['repair_charges'],
                'other_charges' => $other,
                'penalty' => 0,
                'due_date' => $validated['due_date'],
                'payment_status' => 'unpaid',
            ]);

            $generatedCount++;
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'BULK_GENERATE_BILLS',
            'module' => 'Admin - Billing',
            'record_id' => 0,
            'new_values' => json_encode(['generated_count' => $generatedCount, 'month' => $validated['billing_month']]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.bills.index')->with('success', "Generated maintenance bills for {$generatedCount} occupied flats.");
    }
}
