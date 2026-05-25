@extends('layouts.app')
@section('title', 'Announcements')
@section('content')

<div style="display:flex;flex-direction:column;gap:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:20px;font-weight:700;color:#fff;">Announcements</h1>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">{{ $announcements->total() }} announcements total</p>
        </div>
        <button class="btn-primary" onclick="openModal('add-announcement-modal')">
            <i class="fa-solid fa-plus"></i> New Announcement
        </button>
    </div>

    {{-- List --}}
    <div style="display:flex;flex-direction:column;gap:12px;">
        @forelse($announcements as $ann)
        @php
            $typeColor = match($ann->type) {
                'urgent'      => ['bg' => 'rgba(239,68,68,.12)',  'border' => 'rgba(239,68,68,.25)',  'badge' => 'badge-error',   'dot' => '#ef4444'],
                'maintenance' => ['bg' => 'rgba(245,158,11,.12)', 'border' => 'rgba(245,158,11,.25)', 'badge' => 'badge-warning', 'dot' => '#f59e0b'],
                default       => ['bg' => 'rgba(59,130,246,.10)', 'border' => 'rgba(59,130,246,.20)', 'badge' => 'badge-info',    'dot' => '#60a5fa'],
            };
        @endphp
        <div style="background:var(--barca-card);border:1px solid var(--barca-border);border-radius:12px;padding:20px;border-left:3px solid {{ $typeColor['dot'] }};">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                        <span class="badge {{ $typeColor['badge'] }}" style="text-transform:capitalize;">{{ $ann->type }}</span>
                        <span style="font-size:12px;color:#64748b;">{{ $ann->created_at->diffForHumans() }}</span>
                        <span style="font-size:12px;color:#64748b;">by {{ $ann->author->name }}</span>
                    </div>
                    <p style="font-size:15px;font-weight:600;color:#fff;margin-bottom:6px;">{{ $ann->title }}</p>
                    <p style="font-size:13px;color:#94a3b8;line-height:1.6;margin:0;">{{ $ann->body }}</p>
                </div>
                <form method="POST" action="{{ route('announcements.destroy', $ann) }}" onsubmit="return confirm('Delete this announcement?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="padding:6px;border-radius:6px;background:none;border:none;color:#64748b;cursor:pointer;flex-shrink:0;"
                        onmouseover="this.style.color='#f87171';this.style.background='rgba(239,68,68,.1)'"
                        onmouseout="this.style.color='#64748b';this.style.background='none'">
                        <i class="fa-solid fa-trash" style="font-size:13px;"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:48px;color:#475569;font-size:13px;">
            <i class="fa-solid fa-bullhorn" style="font-size:32px;margin-bottom:12px;display:block;opacity:.3;"></i>
            No announcements yet.
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($announcements->hasPages())
    <div>{{ $announcements->links() }}</div>
    @endif
</div>

{{-- Add Modal --}}
<div id="add-announcement-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">New Announcement</h2>
            <button onclick="closeModal('add-announcement-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" action="{{ route('announcements.store') }}" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Title *</label>
                <input type="text" name="title" required placeholder="Announcement title" class="input" />
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Type *</label>
                <select name="type" class="input">
                    <option value="general">General</option>
                    <option value="urgent">Urgent</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Message *</label>
                <textarea name="body" required rows="4" placeholder="Write your announcement here..." class="input" style="resize:vertical;"></textarea>
            </div>
            <div style="display:flex;gap:12px;padding-top:4px;">
                <button type="button" onclick="closeModal('add-announcement-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Post Announcement</button>
            </div>
        </form>
    </div>
</div>

@endsection