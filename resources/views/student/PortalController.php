<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class PortalController extends Controller
{
    /**
     * Student portal — profile, active allocation, and history.
     * Returns: resources/views/student/portal.blade.php
     */
    public function index()
    {
        $student     = auth()->user()->student;
        $allocation  = $student?->activeAllocation()->with('room')->first();
        $application = $student?->latestApplication()->with('room')->first();
        $history     = $student?->allocations()->with('room')->latest()->get() ?? collect();

        return view('student.portal', compact('student', 'allocation', 'application', 'history'));
    }

    /**
     * Student room detail page.
     * Returns: resources/views/student/room.blade.php
     */
    public function room()
    {
        $student    = auth()->user()->student;
        $allocation = $student?->activeAllocation()->with('room')->first();

        return view('student.room', compact('student', 'allocation'));
    }
}