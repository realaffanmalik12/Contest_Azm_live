<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Generic "dashboard" route — used as the default redirect target for already-logged-in users
Route::middleware('auth')->get('/dashboard', [AuthController::class, 'redirectToDashboard'])->name('dashboard');

// Guest-only routes — blocked for already-logged-in users
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

// Logout must be accessible only to logged-in users
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected dashboard routes — blocked for logged-out users
Route::middleware('auth')->group(function () {
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Resident & Flat Onboarding
        Route::resource('flats', \App\Http\Controllers\Admin\FlatController::class);
        Route::get('flats/{flat}/residents/create', [\App\Http\Controllers\Admin\ResidentController::class, 'create'])->name('residents.create');
        Route::post('flats/{flat}/residents', [\App\Http\Controllers\Admin\ResidentController::class, 'store'])->name('residents.store');
        Route::post('residents/{user}/offboard', [\App\Http\Controllers\Admin\ResidentController::class, 'offboard'])->name('residents.offboard');

        // Automated Billing Engine
        Route::get('bills', [\App\Http\Controllers\Admin\BillingController::class, 'index'])->name('bills.index');
        Route::get('bills/create', [\App\Http\Controllers\Admin\BillingController::class, 'create'])->name('bills.create');
        Route::post('bills/generate', [\App\Http\Controllers\Admin\BillingController::class, 'generateBulk'])->name('bills.generate');

        // Helpdesk / Complaints SLA
        Route::get('complaints', [\App\Http\Controllers\Admin\HelpdeskController::class, 'index'])->name('complaints.index');
        Route::post('complaints/{complaint}/assign', [\App\Http\Controllers\Admin\HelpdeskController::class, 'assignStaff'])->name('complaints.assign');

        // Security Gate Logs
        Route::get('gate-logs', [\App\Http\Controllers\Admin\SecuritySupervisionController::class, 'index'])->name('gate-logs.index');

        // Emergency Broadcast
        Route::get('emergency-alerts', [\App\Http\Controllers\Admin\EmergencyBroadcastController::class, 'index'])->name('emergency-alerts.index');
        Route::post('emergency-alerts', [\App\Http\Controllers\Admin\EmergencyBroadcastController::class, 'store'])->name('emergency-alerts.store');
    });

    Route::middleware(['auth', 'role:resident'])->prefix('resident')->name('resident.')->group(function () {
        Route::get('/dashboard', function () {
            return view('resident.dashboard');
        })->name('dashboard');

        // Profile & Vehicle Management
        Route::get('profile', [\App\Http\Controllers\Resident\ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile', [\App\Http\Controllers\Resident\ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('family', [\App\Http\Controllers\Resident\ProfileController::class, 'storeFamilyMember'])->name('family.store');
        Route::delete('family/{familyMember}', [\App\Http\Controllers\Resident\ProfileController::class, 'destroyFamilyMember'])->name('family.destroy');
        Route::post('vehicles', [\App\Http\Controllers\Resident\ProfileController::class, 'storeVehicle'])->name('vehicles.store');
        Route::delete('vehicles/{vehicle}', [\App\Http\Controllers\Resident\ProfileController::class, 'destroyVehicle'])->name('vehicles.destroy');

        // Maintenance & Invoices
        Route::get('bills', [\App\Http\Controllers\Resident\BillingController::class, 'index'])->name('bills.index');
        Route::get('bills/{bill}', [\App\Http\Controllers\Resident\BillingController::class, 'show'])->name('bills.show');
        Route::post('bills/{bill}/pay', [\App\Http\Controllers\Resident\BillingController::class, 'pay'])->name('bills.pay');

        // Visitor Pre-Approval & Gate Pass (Supports both hyphen and underscore paths)
        Route::get('visitor-passes', [\App\Http\Controllers\Resident\VisitorPassController::class, 'index'])->name('visitor-passes.index');
        Route::get('visitor_passes', [\App\Http\Controllers\Resident\VisitorPassController::class, 'index']);
        Route::get('visitor-passes/create', [\App\Http\Controllers\Resident\VisitorPassController::class, 'create'])->name('visitor-passes.create');
        Route::get('visitor_passes/create', [\App\Http\Controllers\Resident\VisitorPassController::class, 'create']);
        Route::post('visitor-passes', [\App\Http\Controllers\Resident\VisitorPassController::class, 'store'])->name('visitor-passes.store');
        Route::post('visitor_passes', [\App\Http\Controllers\Resident\VisitorPassController::class, 'store']);
        Route::get('visitor-passes/{visitor}', [\App\Http\Controllers\Resident\VisitorPassController::class, 'show'])->name('visitor-passes.show');
        Route::get('visitor_passes/{visitor}', [\App\Http\Controllers\Resident\VisitorPassController::class, 'show']);

        // Helpdesk Portal
        Route::get('complaints', [\App\Http\Controllers\Resident\ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('complaints/create', [\App\Http\Controllers\Resident\ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('complaints', [\App\Http\Controllers\Resident\ComplaintController::class, 'store'])->name('complaints.store');

        // Amenity Booking
        Route::get('amenity-bookings', [\App\Http\Controllers\Resident\AmenityBookingController::class, 'index'])->name('amenity-bookings.index');
        Route::post('amenity-bookings', [\App\Http\Controllers\Resident\AmenityBookingController::class, 'store'])->name('amenity-bookings.store');

        // Notice Board & Polling
        Route::get('notices-polls', [\App\Http\Controllers\Resident\NoticePollController::class, 'index'])->name('notices-polls.index');
        Route::post('polls/{poll}/vote', [\App\Http\Controllers\Resident\NoticePollController::class, 'vote'])->name('notices-polls.vote');
    });

    Route::middleware(['auth', 'role:guard'])->prefix('guard')->name('guard.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Guard\GateManagementController::class, 'dashboard'])->name('dashboard');
        Route::post('/verify-pass', [\App\Http\Controllers\Guard\GateManagementController::class, 'verifyPass'])->name('pass.verify');
        Route::post('/walkin', [\App\Http\Controllers\Guard\GateManagementController::class, 'logWalkIn'])->name('walkin.store');
        Route::post('/checkout/{gateLog}', [\App\Http\Controllers\Guard\GateManagementController::class, 'checkout'])->name('checkout');
    });

    Route::middleware(['auth', 'role:maintenance'])->prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Maintenance\WorkTicketController::class, 'index'])->name('dashboard');
        Route::post('/tickets/{complaint}/update', [\App\Http\Controllers\Maintenance\WorkTicketController::class, 'updateStatus'])->name('tickets.update');
    });

    // Common Directory & Guidelines Route
    Route::get('/directory-guidelines', [\App\Http\Controllers\SystemDirectoryController::class, 'directory'])->name('directory.guidelines');
});