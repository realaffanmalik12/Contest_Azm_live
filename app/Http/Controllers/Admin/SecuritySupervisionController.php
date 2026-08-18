<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GateLog;
use Illuminate\Http\Request;

class SecuritySupervisionController extends Controller
{
    public function index(Request $request)
    {
        $query = GateLog::with(['visitor', 'flat', 'guard']);

        if ($request->filled('date')) {
            $query->whereDate('entry_time', $request->date);
        }

        if ($request->filled('flat_id')) {
            $query->where('flat_id', $request->flat_id);
        }

        $logs = $query->orderBy('entry_time', 'desc')->paginate(20);

        return view('admin.gate_logs.index', compact('logs'));
    }
}
