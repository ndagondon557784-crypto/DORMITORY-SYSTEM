<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Allocation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::with(['student.user', 'room'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, function ($q, $s) {
                $q->whereHas('student', fn ($sq) =>
                    $sq->where('student_number', 'like', "%$s%")
                       ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$s%"))
                );
            })
            ->latest()->paginate(12)->withQueryString();

        $counts = [
            'pending'  => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        return view('admin.applications.index', compact('applications', 'counts'));
    }

    public function show(Application $application)
    {
        $application->load(['student.user', 'room', 'reviewer']);
        return view('admin.applications.show', compact('application'));
    }

    public function approve(Request $request, Application $application)
    {
        $request->validate(['admin_notes' => ['nullable', 'string', 'max:1000']]);

        if (! $application->isPending()) {
            return back()->with('error', 'Application is no longer pending.');
        }

        $room    = $application->room;
        $student = $application->student;

        if ($room->is_full) {
            return back()->with('error', 'Room is now full. Cannot approve.');
        }

        if ($student->activeAllocation()->exists()) {
            return back()->with('error', 'Student already has an active allocation.');
        }

        DB::transaction(function () use ($application, $room, $student, $request) {
            $application->update([
                'status'      => 'approved',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            Allocation::create([
                'student_id'      => $student->id,
                'room_id'         => $room->id,
                'allocation_date' => now()->toDateString(),
                'end_date'        => now()->addYear()->toDateString(),
                'status'          => 'active',
                'notes'           => "Auto-created from Application #{$application->id}",
            ]);

            // Auto-sync room status
            $occupied = $room->activeAllocations()->count();
            if ($occupied >= $room->capacity) {
                $room->update(['status' => 'full']);
            }

            // Auto-reject other pending applications for same student
            Application::where('student_id', $student->id)
                ->where('id', '!=', $application->id)
                ->where('status', 'pending')
                ->update([
                    'status'      => 'rejected',
                    'admin_notes' => 'Auto-rejected: another application was approved.',
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->id(),
                ]);

            ActivityLog::record(
                'approve',
                "Application #{$application->id} approved — Room {$room->room_number} → {$student->user->name}",
                'Application',
                $application->id
            );
        });

        return redirect()->route('admin.applications.index')
            ->with('success', "Approved. Room {$room->room_number} assigned to {$student->user->name}.");
    }

    public function reject(Request $request, Application $application)
    {
        $request->validate(['admin_notes' => ['required', 'string', 'max:1000']]);

        if (! $application->isPending()) {
            return back()->with('error', 'Application is no longer pending.');
        }

        $application->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        ActivityLog::record('reject', "Application #{$application->id} rejected", 'Application', $application->id);

        return redirect()->route('admin.applications.index')
            ->with('success', 'Application rejected.');
    }
}