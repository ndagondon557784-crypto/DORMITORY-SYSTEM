<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student profile not found.');
        }

        return view('student.profile-edit', compact('student'));
    }

    public function update(Request $request)
    {
        $user    = auth()->user();
        $student = $user->student;

        if (! $student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student profile not found.');
        }

        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'course'            => ['required', 'string', 'max:255'],
            'year_level'        => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('current_password') || $request->filled('password')) {
            $request->validate([
                'current_password' => ['required'],
                'password'         => ['required', 'confirmed', Password::min(8)],
            ]);

            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->update(['password' => Hash::make($request->password)]);
        }

        $student->update([
            'phone'             => $request->phone,
            'address'           => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'course'            => $request->course,
            'year_level'        => $request->year_level,
        ]);

        ActivityLog::record('update', 'Student updated profile', 'Student', $student->id);

        return back()->with('success', 'Profile updated successfully.');
    }
}