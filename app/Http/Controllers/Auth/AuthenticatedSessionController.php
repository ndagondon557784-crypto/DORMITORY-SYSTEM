<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     *
     * ROLE-BASED REDIRECT LOGIC:
     * 1. Validate email + password inputs
     * 2. Attempt authentication against the users table
     * 3. If credentials are wrong → ValidationException (shows error on form)
     * 4. If credentials are correct → regenerate session (security)
     * 5. Check the authenticated user's role column:
     *    role = 'admin'   → redirect to /dashboard (DashboardController serves admin view)
     *    role = 'student' → redirect to /dashboard (DashboardController serves student view)
     *
     * Both go to /dashboard — the DashboardController checks the role
     * and returns the correct view automatically.
     */
    public function store(Request $request)
    {
        // Step 1: Validate form inputs
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Step 2: Try to authenticate
        if (! Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            // Wrong email or password — show error on the form field
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Step 3: Regenerate session ID to prevent session fixation attacks
        $request->session()->regenerate();

        // Step 4: Log the activity
        ActivityLog::record('login', 'User logged in: ' . auth()->user()->email);

        // Step 5: Redirect — DashboardController handles role detection
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout — destroy session completely.
     */
    public function destroy(Request $request)
    {
        ActivityLog::record('logout', 'User logged out: ' . auth()->user()->email);

        Auth::guard('web')->logout();

        // Invalidate and regenerate the session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}