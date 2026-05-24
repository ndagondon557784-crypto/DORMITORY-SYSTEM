<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::with('user')
            ->latest()
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('announcements.create');
    }

    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        Announcement::create([
            'user_id' => Auth::id(),
            ...$request->validated(),
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully');
    }

    public function show(Announcement $announcement): View
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement): View
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update($request->validated());

        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Announcement updated successfully');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully');
    }

    public function pin(Announcement $announcement): RedirectResponse
    {
        $announcement->update(['is_pinned' => !$announcement->is_pinned]);

        return back()->with('success', 'Announcement pinned status updated');
    }
}