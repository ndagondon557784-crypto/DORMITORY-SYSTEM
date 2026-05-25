<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // ── List ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('name',  'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role_id'), fn ($q) => $q->where('role_id', $request->role_id))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $roles = Role::orderBy('display_name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    // ── Show (for detail page if needed) ─────────────────────────────────────
    public function show(User $user)
    {
        $user->load(['role', 'student', 'activityLogs' => fn ($q) => $q->latest()->take(10)]);
        return view('users.show', compact('user'));
    }

    // ── Create form ───────────────────────────────────────────────────────────
    public function create()
    {
        $roles = Role::orderBy('display_name')->get();
        return view('users.create', compact('roles'));
    }

    // ── Store new user ────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role_id'               => ['required', 'integer', 'exists:roles,id'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required', 'string'],
            'is_active'             => ['nullable'],
        ], [
            'name.required'                  => 'Full name is required.',
            'email.required'                 => 'Email address is required.',
            'email.unique'                   => 'This email address is already registered.',
            'role_id.required'               => 'Please select a role.',
            'role_id.exists'                 => 'The selected role does not exist.',
            'password.required'              => 'Password is required.',
            'password.confirmed'             => 'Password confirmation does not match.',
            'password.min'                   => 'Password must be at least 8 characters.',
            'password_confirmation.required' => 'Please confirm the password.',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role_id'   => (int) $validated['role_id'],
            'phone'     => $validated['phone'] ?? null,
            'password'  => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log(
            'create',
            "Created user account: {$user->name} ({$user->email})",
            'User',
            $user->id
        );

        return redirect()
            ->route('users.index')
            ->with('success', "Account for \"{$user->name}\" created successfully.");
    }

    // ── Edit form ─────────────────────────────────────────────────────────────
    public function edit(User $user)
    {
        $roles = Role::orderBy('display_name')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    // ── Update ────────────────────────────────────────────────────────────────
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'role_id'  => ['required', 'integer', 'exists:roles,id'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'is_active' => ['nullable'],
        ], [
            'name.required'      => 'Full name is required.',
            'email.required'     => 'Email address is required.',
            'email.unique'       => 'This email is already used by another account.',
            'role_id.required'   => 'Please select a role.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min'       => 'Password must be at least 8 characters.',
        ]);

        $updateData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role_id'   => (int) $validated['role_id'],
            'phone'     => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        // Only update password if a new one was provided
        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        ActivityLog::log(
            'update',
            "Updated user account: {$user->name} ({$user->email})",
            'User',
            $user->id
        );

        return redirect()
            ->route('users.index')
            ->with('success', "Account for \"{$user->name}\" updated successfully.");
    }

    // ── Delete ────────────────────────────────────────────────────────────────
    public function destroy(User $user)
    {
        // Cannot delete yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Cannot delete the last admin
        if ($user->isAdmin()) {
            $adminCount = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the last administrator account.');
            }
        }

        $name = $user->name;
        $id   = $user->id;

        $user->delete();

        ActivityLog::log('delete', "Deleted user account: {$name}", 'User', $id);

        return redirect()
            ->route('users.index')
            ->with('success', "Account for \"{$name}\" deleted successfully.");
    }

    // ── Toggle active status (AJAX) ───────────────────────────────────────────
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'error'   => 'You cannot deactivate your own account.',
            ], 422);
        }

        $user->update(['is_active' => ! $user->is_active]);

        ActivityLog::log(
            'toggle',
            "Account for {$user->name} " . ($user->is_active ? 'activated' : 'deactivated'),
            'User',
            $user->id
        );

        return response()->json([
            'success'   => true,
            'is_active' => $user->is_active,
            'message'   => "Account " . ($user->is_active ? 'activated' : 'deactivated') . " successfully.",
        ]);
    }
}