@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.users.index') }}">Users</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>{{ $user->name }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
            <i data-feather="edit-2" class="w-4 h-4"></i> Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <div class="card p-6 text-center">
            <img src="{{ $user->avatar_url }}" class="w-24 h-24 rounded-full mx-auto object-cover mb-4 ring-4 ring-primary-100 dark:ring-primary-900">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            <div class="flex items-center justify-center gap-2 mt-3">
                <span class="{{ $user->role === 'admin' ? 'badge-danger' : 'badge-info' }} capitalize">{{ $user->role }}</span>
                <span class="{{ $user->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Account Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <i data-feather="phone" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ $user->phone ?? 'Not set' }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <i data-feather="clock" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">Last login: {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <i data-feather="calendar" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">Joined {{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </dl>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div class="card p-4 text-center">
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->allocations()->count() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Allocations</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->payments()->count() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Payments</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->activityLogs()->count() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Actions</p>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($user->activityLogs()->latest()->take(20)->get() as $log)
                <div class="px-6 py-3 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-feather="{{ $log->action_icon }}" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 dark:text-gray-200">{{ $log->description }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $log->created_at->diffForHumans() }} · {{ $log->ip_address }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <i data-feather="activity" class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No activity yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection