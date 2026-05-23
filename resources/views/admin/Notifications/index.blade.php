@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Notifications</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
    </div>
    <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
        @csrf
        <button type="submit" class="btn-secondary">
            <i data-feather="check-circle" class="w-4 h-4"></i> Mark All Read
        </button>
    </form>
</div>

<div class="card divide-y divide-gray-100 dark:divide-gray-700">
    @forelse($notifications as $notif)
    <div class="px-6 py-4 flex items-start gap-4 {{ !$notif->is_read ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5
            {{ match($notif->type) {
                'success' => 'bg-green-100 dark:bg-green-900/30',
                'warning' => 'bg-yellow-100 dark:bg-yellow-900/30',
                'danger'  => 'bg-red-100 dark:bg-red-900/30',
                default   => 'bg-blue-100 dark:bg-blue-900/30',
            } }}">
            <i data-feather="{{ match($notif->type) {
                'success' => 'check-circle',
                'warning' => 'alert-triangle',
                'danger'  => 'alert-circle',
                default   => 'bell',
            } }}" class="w-5 h-5 {{ match($notif->type) {
                'success' => 'text-green-600 dark:text-green-400',
                'warning' => 'text-yellow-600 dark:text-yellow-400',
                'danger'  => 'text-red-600 dark:text-red-400',
                default   => 'text-blue-600 dark:text-blue-400',
            } }}"></i>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white {{ !$notif->is_read ? 'font-semibold' : '' }}">{{ $notif->title }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ $notif->message }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(!$notif->is_read)
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    @endif
                    <p class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if($notif->link)
            <a href="{{ $notif->link }}" class="text-xs text-primary-600 dark:text-primary-400 hover:underline mt-1 inline-block">View details →</a>
            @endif
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            @if(!$notif->is_read)
            <form action="{{ route('admin.notifications.read', $notif) }}" method="POST">
                @csrf
                <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors" title="Mark as read">
                    <i data-feather="check" class="w-4 h-4"></i>
                </button>
            </form>
            @endif
            <form action="{{ route('admin.notifications.destroy', $notif) }}" method="POST"
                  onsubmit="return confirm('Delete this notification?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="px-6 py-16 text-center">
        <i data-feather="bell-off" class="w-14 h-14 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
        <p class="text-xl font-semibold text-gray-500 dark:text-gray-400">No notifications</p>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">You're all caught up!</p>
    </div>
    @endforelse
</div>

@if($notifications->hasPages())
<div class="card p-4 mt-4">{{ $notifications->links() }}</div>
@endif
@endsection