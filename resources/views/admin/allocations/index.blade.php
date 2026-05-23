@extends('layouts.app')
@section('title', 'Allocations')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Allocations</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Room Allocations</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage student room assignments</p>
    </div>
    <a href="{{ route('admin.allocations.create') }}" class="btn-primary">
        <i data-feather="plus" class="w-4 h-4"></i> New Allocation
    </a>
</div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student name or ID..." class="form-input pl-9">
        </div>
        <select name="status" class="form-input w-full sm:w-40">
            <option value="">All Status</option>
            @foreach(['active','checked_out','cancelled','expired'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','status']))
        <a href="{{ route('admin.allocations.index') }}" class="btn-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Expected Check-out</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Allocated By</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($allocations as $alloc)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $alloc->student->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $alloc->student->full_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $alloc->student->student_id }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="font-medium">Room {{ $alloc->room->room_number }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $alloc->room->dormitory->name }}</p>
                    </td>
                    <td>{{ $alloc->check_in_date->format('M d, Y') }}</td>
                    <td>{{ $alloc->expected_check_out_date?->format('M d, Y') ?? '—' }}</td>
                    <td>{{ $alloc->duration_in_days }}d</td>
                    <td><span class="{{ $alloc->status_badge }} capitalize">{{ str_replace('_',' ',$alloc->status) }}</span></td>
                    <td class="text-xs text-gray-500 dark:text-gray-400">{{ $alloc->allocatedBy->name }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.allocations.show', $alloc) }}" class="p-1.5 rounded-lg text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors" title="View">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                            @if($alloc->status === 'active')
                            <form action="{{ route('admin.allocations.checkout', $alloc) }}" method="POST"
                                  onsubmit="return confirm('Check out {{ $alloc->student->full_name }}?')">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors" title="Check Out">
                                    <i data-feather="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.allocations.edit', $alloc) }}" class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors" title="Edit">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </a>
                            @if($alloc->status !== 'active')
                            <form action="{{ route('admin.allocations.destroy', $alloc) }}" method="POST"
                                  onsubmit="return confirm('Delete this allocation?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <i data-feather="key" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No allocations found</p>
                        <a href="{{ route('admin.allocations.create') }}" class="btn-primary mt-4 inline-flex">
                            <i data-feather="plus" class="w-4 h-4"></i> New Allocation
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($allocations->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $allocations->links() }}</div>
    @endif
</div>
@endsection