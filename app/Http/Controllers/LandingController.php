<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;

class LandingController extends Controller
{
    public function index()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'total_students' => Student::where('status', 'active')->count(),
        ];
        $featuredRooms = Room::where('status', 'available')->latest()->take(3)->get();
        return view('landing', compact('stats', 'featuredRooms'));
    }
}