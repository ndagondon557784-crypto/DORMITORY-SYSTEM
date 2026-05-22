<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        ActivityLog::record('create', "Admin {$admin->name} created", 'User', $admin->id);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created.');
    }

    public function show(User $admin)
    {
        abort_if($admin->role !== 'admin', 404);
        return view('admin.admins.show', compact('admin'));
    }

    public function edit(User $admin)
    {
        abort_if($admin->role !== 'admin', 404);
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        abort_if($admin->role !== 'admin', 404);

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $admin->id],
        ]);

        $admin->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['min:8', 'confirmed']]);
            $admin->update(['password' => Hash::make($request->password)]);
        }

        ActivityLog::record('update', "Admin {$admin->name} updated", 'User', $admin->id);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated.');
    }

    public function destroy(User $admin)
    {
        abort_if($admin->role !== 'admin', 404);

        if ($admin->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        if (User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last admin.');
        }

        $name = $admin->name;
        $admin->delete();
        ActivityLog::record('delete', "Admin $name deleted");

        return redirect()->route('admin.admins.index')->with('success', "$name deleted.");
    }
}