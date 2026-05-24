<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::with('user', 'allocations')
            ->paginate(15);

        return view('students.index', compact('students'));
    }

    public function create(): View
    {
        return view('students.create');
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'course' => $request->course,
            'year' => $request->year,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully');
    }

    public function show(Student $student): View
    {
        $student->load('user', 'allocations', 'payments');
        return view('students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        if ($request->has('name') || $request->has('email')) {
            $student->user->update($request->only('name', 'email'));
        }

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }

    public function search()
    {
        $query = request()->input('query');
        $students = Student::where('student_id', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->with('user', 'allocations')
            ->paginate(15);

        return view('students.index', compact('students'));
    }
}