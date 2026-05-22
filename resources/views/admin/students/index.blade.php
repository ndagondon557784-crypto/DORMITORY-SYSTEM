@extends('layouts.app')
@section('title', 'Students')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Students</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Students</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage student tenant records</p>
    </div>
    <a href="{{ route('admin.students.create') }}" class="btn-primary">
        <i data-feather="plus" class="w-4 h-4"></i> Add Student
    </a>
</div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name, ID, email, course..."
                   class="form-input pl-9">
        </div>
        <select name="status" class="form-input w-full sm:w-40">
            <option value="">All Status</option>
            @foreach(['active','inactive','graduated','suspended'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="gender" class="form-input w-full sm:w-36">
            <option value="">All Gender</option>
            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','status','gender']))
        <a href="{{ route('admin.students.index') }}" class="btn-secondary">Clear</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Course & Year</th>
                    <th>Room</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($students as $student)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $student->avatar_url }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $student->full_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="font-mono text-xs">{{ $student->student_id }}</td>
                    <td>
                        <p class="text-sm">{{ $student->course }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Year {{ $student->year_level }}</p>
                    </td>
                    <td>
                        @if($student->activeAllocation)
                            <p class="text-sm font-medium">Room {{ $student->activeAllocation->room->room_number }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $student->activeAllocation->room->dormitory->name }}</p>
                        @else
                            <span class="text-xs text-gray-400 dark:text-gray-500">Not assigned</span>
                        @endif
                    </td>
                    <td><span class="{{ $student->status_badge }}">{{ ucfirst($student->status) }}</span></td>
                    <td class="text-xs text-gray-500 dark:text-gray-400">{{ $student->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.students.show', $student) }}"
                               class="p-1.5 rounded-lg text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors" title="View">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.students.edit', $student) }}"
                               class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors" title="Edit">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $student->full_name }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i data-feather="users" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No students found</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Add your first student to get started</p>
                        <a href="{{ route('admin.students.create') }}" class="btn-primary mt-4 inline-flex">
                            <i data-feather="plus" class="w-4 h-4"></i> Add Student
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection