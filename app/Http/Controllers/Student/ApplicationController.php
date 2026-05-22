<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Room;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function create()
    {
        $student = auth()->user()->student;

        if (! $student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student profile not found. Please contact admin.');
        }

        if ($student->activeAllocation()->exists()) {
            return redirect()->route('student.room')
                ->with('error', 'You already have an active room allocation.');
        }

        if ($student->pendingApplication()->exists()) {
            return redirect()->route('student.application.status')
                ->with('error', 'You already have a pending application. Please wait for admin review.');
        }

        $rooms = Room::where('status', 'available')
            ->where(function ($q) use ($student) {
                $q->where('gender', $student->gender)->orWhere('gender', 'mixed');
            })
            ->withCount(['activeAllocations as occupied'])
            ->get()
            ->filter(fn ($r) => $r->occupied < $r->capacity)
            ->values();

        return view('student.apply', compact('student', 'rooms'));
    }

    public function store(Request $request)
    {
        $student = auth()->user()->student;

        if (! $student) {
            return back()->with('error', 'Student profile not found.');
        }

        $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'reason'  => ['nullable', 'string', 'max:1000'],
        ]);

        if ($student->activeAllocation()->exists()) {
            return back()->with('error', 'You already have an active room allocation.');
        }

        if ($student->pendingApplication()->exists()) {
            return back()->with('error', 'You already have a pending application.');
        }

        $room = Room::findOrFail($request->room_id);

        if ($room->status === 'maintenance') {
            return back()->with('error', 'This room is under maintenance.');
        }

        if ($room->is_full) {
            return back()->with('error', 'This room is now full. Please choose another room.');
        }

        $application = Application::create([
            'student_id' => $student->id,
            'room_id'    => $request->room_id,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        ActivityLog::record(
            'apply',
            "Student {$student->user->name} applied for Room {$room->room_number}",
            'Application',
            $application->id
        );

        return redirect()->route('student.application.status')
            ->with('success', "Application submitted for Room {$room->room_number}. Awaiting admin approval.");
    }

    public function cancel(Request $request, Application $application)
    {
        $student = auth()->user()->student;

        if (! $student || $application->student_id !== $student->id) {
            abort(403);
        }

        if (! $application->isPending()) {
            return back()->with('error', 'Only pending applications can be cancelled.');
        }

        $application->update([
            'status'      => 'rejected',
            'admin_notes' => 'Cancelled by student.',
            'reviewed_at' => now(),
        ]);

        ActivityLog::record('cancel', "Student cancelled Application #{$application->id}");

        return redirect()->route('student.application.status')
            ->with('success', 'Your application has been cancelled.');
    }

    public function status()
    {
        $student      = auth()->user()->student;
        $applications = $student ? $student->applications()->with('room')->latest()->get() : collect();
        $allocation   = $student ? $student->activeAllocation()->with('room')->first() : null;

        return view('student.application-status', compact('student', 'applications', 'allocation'));
    }
}