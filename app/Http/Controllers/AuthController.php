<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $attempt = Auth::attempt(
            [$loginType => $credentials['login'], 'password' => $credentials['password']],
            $request->boolean('remember')
        );

        if (! $attempt) {
            return back()->withErrors([
                'login' => 'Invalid credentials. Please try again.',
            ])->onlyInput('login');
        }

        if (Auth::user()->status !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'login' => 'This account is not active. Please contact the administrator.',
            ])->onlyInput('login');
        }

        $request->session()->regenerate();

        return $this->redirectToDashboard();
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Central place that decides which dashboard a user belongs on.
     * Used after login, AND used by the "guest" redirect below.
     */
    public function redirectToDashboard()
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'resident' => redirect()->route('resident.dashboard'),
            'guard' => redirect()->route('guard.dashboard'),
            'maintenance' => redirect()->route('maintenance.dashboard'),
            default => redirect()->route('login'),
        };
    }
}