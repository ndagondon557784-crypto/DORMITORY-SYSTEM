@extends('layouts.app')
@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('breadcrumb', 'Announcements')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Announcements</h2>
    @if(auth()->user()->isStaff())
    <a href="{{ route('announcements.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Post Announcement
    </a>
    @endif
</div>

<div class="space-y-4">
    @forelse($announcements as $ann)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-4 flex-1">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white flex-shrink-0
                    {{ match($ann->type) { 'urgent'=>'bg-red-500', 'maintenance'=>'bg-yellow-500', 'event'=>'bg-blue-500', default=>'bg-emerald-500' } }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-semibold text-gray-800 dark:text-white">{{ $ann->title }}</h3>
                        <span class="badge {{ match($ann->type) { 'urgent'=>'badge-red', 'maintenance'=>'badge-yellow', 'event'=>'badge-blue', default=>'badge-green' } }}">
                            {{ ucfirst($ann->type) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">{{ $ann->content }}</p>
                    <p class="text-xs text-gray-400 mt-3">Posted by {{ $ann->author->name }} · {{ $ann->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if(auth()->user()->isStaff())
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('announcements.edit', $ann) }}" class="text-xs text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('announcements.destroy', $ann) }}" method="POST" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 hover:text-red-700">Delete</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-400">
        No announcements yet.
    </div>
    @endforelse

    {{ $announcements->links() }}
</div>
@endsection