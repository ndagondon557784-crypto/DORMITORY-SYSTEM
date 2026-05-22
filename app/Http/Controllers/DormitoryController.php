<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ActivityLog, Dormitory};
use Illuminate\Http\Request;

class DormitoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Dormitory::withCount('rooms');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('code', 'like', "%{$request->search}%");
        }

        $dormitories = $query->latest()->paginate(15)->withQueryString();

        return view('admin.dormitories.index', compact('dormitories'));
    }

    public function create()
    {
        return view('admin.dormitories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:dormitories,code',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'total_capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $dorm = Dormitory::create($validated);
        ActivityLog::record('create', "Created dormitory: {$dorm->name}", $dorm);

        return redirect()->route('admin.dormitories.index')
            ->with('success', "Dormitory {$dorm->name} created successfully.");
    }

    public function show(Dormitory $dormitory)
    {
        $dormitory->load('rooms');
        return view('admin.dormitories.show', compact('dormitory'));
    }

    public function edit(Dormitory $dormitory)
    {
        return view('admin.dormitories.edit', compact('dormitory'));
    }

    public function update(Request $request, Dormitory $dormitory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => "required|string|max:20|unique:dormitories,code,{$dormitory->id}",
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'total_capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $old = $dormitory->toArray();
        $dormitory->update($validated);
        ActivityLog::record('update', "Updated dormitory: {$dormitory->name}", $dormitory, $old, $dormitory->fresh()->toArray());

        return redirect()->route('admin.dormitories.show', $dormitory)
            ->with('success', "Dormitory {$dormitory->name} updated successfully.");
    }

    public function destroy(Dormitory $dormitory)
    {
        if ($dormitory->rooms()->count() > 0) {
            return back()->with('error', 'Cannot delete a dormitory that has rooms. Please remove all rooms first.');
        }

        $name = $dormitory->name;
        ActivityLog::record('delete', "Deleted dormitory: {$name}", $dormitory);
        $dormitory->delete();

        return redirect()->route('admin.dormitories.index')
            ->with('success', "Dormitory {$name} deleted successfully.");
    }
}