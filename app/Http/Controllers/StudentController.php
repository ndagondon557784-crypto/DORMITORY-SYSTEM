<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ActivityLog, Student};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('activeAllocation.room.dormitory');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('course', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'course' => 'required|string|max:100',
            'year_level' => 'required|integer|min:1|max:10',
            'home_address' => 'required|string',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relation' => 'required|string|max:50',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive,graduated,suspended',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars/students', 'public');
        }

        $student = Student::create($validated);
        ActivityLog::record('create', "Created student: {$student->full_name}", $student);

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$student->full_name} created successfully.");
    }

    public function show(Student $student)
    {
        $student->load(['allocations.room.dormitory', 'payments.allocation']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => "required|string|unique:students,student_id,{$student->id}",
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => "required|email|unique:students,email,{$student->id}",
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'course' => 'required|string|max:100',
            'year_level' => 'required|integer|min:1|max:10',
            'home_address' => 'required|string',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relation' => 'required|string|max:50',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive,graduated,suspended',
            'notes' => 'nullable|string',
        ]);

        $old = $student->toArray();

        if ($request->hasFile('avatar')) {
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars/students', 'public');
        }

        $student->update($validated);
        ActivityLog::record('update', "Updated student: {$student->full_name}", $student, $old, $student->fresh()->toArray());

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student {$student->full_name} updated successfully.");
    }

    public function destroy(Student $student)
    {
        if ($student->activeAllocation) {
            return back()->with('error', 'Cannot delete a student with an active room allocation.');
        }

        $name = $student->full_name;
        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }
        ActivityLog::record('delete', "Deleted student: {$name}", $student);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$name} deleted successfully.");
    }
}