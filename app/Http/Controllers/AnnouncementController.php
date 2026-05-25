<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')->latest()->paginate(15);
        return view('announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
            'type'  => ['required', 'in:general,urgent,maintenance'],
        ]);

        Announcement::create([
            'title'     => $request->title,
            'body'      => $request->body,
            'type'      => $request->type,
            'author_id' => Auth::id(),
        ]);

        return back()->with('success', 'Announcement posted successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}