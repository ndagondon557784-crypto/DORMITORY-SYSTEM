@extends('layouts.app')
@section('title', 'Dormitories')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Dormitories</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dormitories</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage dormitory buildings</p>
    </div>
    <a href="{{ route('admin.dormitories.create') }}" class="btn-primary">
        <i data-feather="plus" class="w-4 h-4"></i> Add Dormitory
    </a>
</div>

<div class="card p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search dormitory name or code..." class="form-input pl-9">
        </div>
        <button type="submit" class="btn-primary">Search</button>
        @if(request('search'))<a href="{{ route('admin.dormitories.index') }}" class="btn-secondary">Clear</a>@endif
    </form>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($dormitories as $dorm)
    <div class="card overflow-hidden hover:shadow-lg transition-shadow">
        <!-- Header strip -->
        <div class="h-2 bg-gradient-to-r from-primary-500 to-purple-500"></div>
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $dorm->name }}</h3>
                    <span class="font-mono text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded mt-1 inline-block">{{ $dorm->code }}</span>
                </div>
                <span class="{{ $dorm->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $dorm->is_active ? 'Active' : 'Inactive' }}</span>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $dorm->rooms_count }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Rooms</p>
                </div>
                <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
                    <p class="text-xl font-bold text-green-600">{{ $dorm->available_rooms_count }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Available</p>
                </div>
                <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                    <p class="text-xl font-bold text-blue-600">{{ $dorm->total_capacity }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Capacity</p>
                </div>
            </div>

            <div class="space-y-1 text-sm mb-4">
                <div class="flex items-start gap-2 text-gray-600 dark:text-gray-400">
                    <i data-feather="map-pin" class="w-3.5 h-3.5 mt-0.5 flex-shrink-0"></i>
                    <span class="truncate">{{ $dorm->address }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <i data-feather="phone" class="w-3.5 h-3.5 flex-shrink-0"></i>
                    <span>{{ $dorm->contact_number }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.dormitories.show', $dorm) }}" class="flex-1 btn-secondary text-xs justify-center py-1.5">
                    <i data-feather="eye" class="w-3 h-3"></i> View
                </a>
                <a href="{{ route('admin.dormitories.edit', $dorm) }}" class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                    <i data-feather="edit-2" class="w-4 h-4"></i>
                </a>
                <form action="{{ route('admin.dormitories.destroy', $dorm) }}" method="POST"
                      onsubmit="return confirm('Delete {{ $dorm->name }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="sm:col-span-2 lg:col-span-3">
        <div class="card p-16 text-center">
            <i data-feather="home" class="w-14 h-14 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
            <p class="text-xl font-semibold text-gray-500 dark:text-gray-400">No dormitories yet</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 mb-6">Add your first dormitory to get started</p>
            <a href="{{ route('admin.dormitories.create') }}" class="btn-primary inline-flex">
                <i data-feather="plus" class="w-4 h-4"></i> Add Dormitory
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($dormitories->hasPages())
<div class="card p-4 mt-6">{{ $dormitories->links() }}</div>
@endif
@endsection