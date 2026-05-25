<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request) {
        $query = Student::with('allocation.room');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('student_id', 'like', "%{$request->search}%")
                  ->orWhere('course', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        return view('students.index', compact('students'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:students',
            'phone'      => 'nullable|string|max:20',
            'course'     => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:6',
            'gender'     => 'required|in:Male,Female',
            'status'     => 'required|in:Active,Inactive,Graduated',
        ]);

        $year = date('Y');
        $last = Student::where('student_id', 'like', "STU-{$year}-%")->count() + 1;
        $data['student_id'] = "STU-{$year}-" . str_pad($last, 3, '0', STR_PAD_LEFT);

        Student::create($data);
        return back()->with('success', 'Student added successfully.');
    }

    public function update(Request $request, Student $student) {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => "required|email|unique:students,email,{$student->id}",
            'phone'      => 'nullable|string|max:20',
            'course'     => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:6',
            'gender'     => 'required|in:Male,Female',
            'status'     => 'required|in:Active,Inactive,Graduated',
        ]);

        $student->update($data);
        return back()->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student) {
        $student->delete();
        return back()->with('success', 'Student deleted successfully.');
    }
}