<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ActivityLog, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Storage};

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);
        ActivityLog::record('create', "Created user: {$user->name} ({$user->role})", $user);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} created successfully.");
    }

    public function show(User $user)
    {
        $this->authorizeAdmin();
        $user->load('activityLogs');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $old = $user->toArray();
        $user->update($validated);
        ActivityLog::record('update', "Updated user: {$user->name}", $user, $old, $user->fresh()->toArray());

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} updated successfully.");
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        ActivityLog::record('delete', "Deleted user: {$name}", $user);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} deleted successfully.");
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$user->id}",
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            }
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars/users', 'public');
        }

        unset($validated['current_password']);
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    private function authorizeAdmin(): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can manage users.');
        }
    }
}