<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle student self-registration.
     *
     * IMPORTANT: This always creates a STUDENT account.
     * Admin accounts are created by other admins through the admin panel.
     *
     * Steps:
     * 1. Validate all inputs — including unique email and unique student_number
     * 2. Create User row with role = 'student'
     * 3. Create Student row linked to the User
     * 4. Login the new user automatically
     * 5. Redirect to dashboard (will show student dashboard)
     */
    public function store(Request $request)
    {
        // Validate all inputs
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'string', 'confirmed', Password::min(8)],
            'student_number' => ['required', 'string', 'max:50', 'unique:students,student_number'],
            'course'         => ['required', 'string', 'max:255'],
            'year_level'     => ['required', 'integer', 'min:1', 'max:6'],
            'gender'         => ['required', 'in:male,female,other'],
            'phone'          => ['nullable', 'string', 'max:20'],
        ]);

        $user = null;

        // Wrap in transaction — if Student::create fails, User::create is rolled back
        DB::transaction(function () use ($request, &$user) {

            // Create the login account — role is always 'student' here
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password), // Never store plain text
                'role'     => 'student',                       // Always student on self-register
            ]);

            // Create the student profile linked to this user
            Student::create([
                'user_id'        => $user->id,
                'student_number' => $request->student_number,
                'course'         => $request->course,
                'year_level'     => $request->year_level,
                'gender'         => $request->gender,
                'phone'          => $request->phone,
            ]);

            // Fire the Registered event
            event(new Registered($user));
        });

        // Log in the new user immediately
        Auth::login($user);

        ActivityLog::record('register', 'New student registered: ' . $user->email);

        // Redirect to dashboard — DashboardController will detect role = 'student'
        // and return the student dashboard view
        return redirect()->route('dashboard')
            ->with('success', 'Welcome! Your account has been created successfully.');
    }
}