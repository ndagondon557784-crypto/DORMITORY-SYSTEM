<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')->latest()->paginate(15);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'type'       => 'required|in:general,urgent,maintenance,event',
            'target'     => 'required|in:all,students,staff',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $data['user_id']      = auth()->id();
        $data['is_published'] = $request->boolean('is_published');

        $announcement = Announcement::create($data);

        ActivityLog::log('create', "Created announcement: {$announcement->title}", 'Announcement', $announcement->id);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement published successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'type'       => 'required|in:general,urgent,maintenance,event',
            'target'     => 'required|in:all,students,staff',
            'is_published' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $announcement->update($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted.');
    }
}