<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\ActivityLog;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['activeAllocation.room.building'])
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%{$s}%")
                  ->orWhere('student_id', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            }))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->gender, fn($q, $g) => $q->where('gender', $g));

        $students = $query->orderBy('full_name')->paginate(15)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        $student = Student::create($data);

        ActivityLog::log('create', "Created student: {$student->full_name}", 'Student', $student->id);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['activeAllocation.room.building', 'payments' => fn($q) => $q->latest()->limit(10)]);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($student->photo) Storage::disk('public')->delete($student->photo);
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        $student->update($data);

        ActivityLog::log('update', "Updated student: {$student->full_name}", 'Student', $student->id);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->activeAllocation) {
            return back()->with('error', 'Cannot delete student with an active allocation.');
        }

        if ($student->photo) Storage::disk('public')->delete($student->photo);

        $student->delete();

        ActivityLog::log('delete', "Deleted student: {$student->full_name}", 'Student', $student->id);

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}