<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['user', 'activeAllocation.room'])
            ->when($request->search, function ($q, $s) {
                $q->where('student_number', 'like', "%$s%")
                  ->orWhere('course', 'like', "%$s%")
                  ->orWhereHas('user', fn ($u) =>
                        $u->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
            })
            ->when($request->gender, fn ($q, $g) => $q->where('gender', $g))
            ->when($request->status === 'assigned',   fn ($q) =>
                $q->whereHas('allocations', fn ($a) => $a->where('status', 'active')))
            ->when($request->status === 'unassigned', fn ($q) =>
                $q->whereDoesntHave('allocations', fn ($a) => $a->where('status', 'active')))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'unique:users,email'],
            'password'          => ['required', 'confirmed', Password::min(8)],
            'student_number'    => ['required', 'string', 'max:50', 'unique:students,student_number'],
            'course'            => ['required', 'string', 'max:255'],
            'year_level'        => ['required', 'integer', 'min:1', 'max:6'],
            'gender'            => ['required', 'in:male,female,other'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'student',
            ]);
            $student = Student::create([
                'user_id'           => $user->id,
                'student_number'    => $request->student_number,
                'course'            => $request->course,
                'year_level'        => $request->year_level,
                'gender'            => $request->gender,
                'phone'             => $request->phone,
                'address'           => $request->address,
                'emergency_contact' => $request->emergency_contact,
            ]);
            ActivityLog::record('create', "Student {$user->name} created", 'Student', $student->id);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student created.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'allocations.room', 'applications.room']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'unique:users,email,' . $student->user_id],
            'student_number'    => ['required', 'string', 'unique:students,student_number,' . $student->id],
            'course'            => ['required', 'string', 'max:255'],
            'year_level'        => ['required', 'integer', 'min:1', 'max:6'],
            'gender'            => ['required', 'in:male,female,other'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'address'           => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update(['name' => $request->name, 'email' => $request->email]);
            if ($request->filled('password')) {
                $request->validate(['password' => ['min:8', 'confirmed']]);
                $student->user->update(['password' => Hash::make($request->password)]);
            }
            $student->update([
                'student_number'    => $request->student_number,
                'course'            => $request->course,
                'year_level'        => $request->year_level,
                'gender'            => $request->gender,
                'phone'             => $request->phone,
                'address'           => $request->address,
                'emergency_contact' => $request->emergency_contact,
            ]);
        });

        ActivityLog::record('update', "Student {$student->user->name} updated", 'Student', $student->id);

        return redirect()->route('admin.students.index')->with('success', 'Student updated.');
    }

    public function destroy(Student $student)
    {
        if ($student->activeAllocation()->exists()) {
            return back()->with('error', 'Cannot delete: student has an active allocation.');
        }

        $name = $student->user->name;
        DB::transaction(function () use ($student) {
            $student->applications()->delete();
            $student->allocations()->delete();
            $student->delete();
            $student->user->delete();
        });

        ActivityLog::record('delete', "Student $name deleted");

        return redirect()->route('admin.students.index')->with('success', "$name deleted.");
    }
}