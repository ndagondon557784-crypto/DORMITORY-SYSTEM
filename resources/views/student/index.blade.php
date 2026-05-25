@extends('layouts.app')
@section('title', 'Students')
@section('page-title', 'Students')
@section('breadcrumb', 'Management / Students')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Student Records</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage all dormitory students</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Student
    </a>
</div>

{{-- Filters --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, ID, or email..." class="form-input">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                <option value="graduated" {{ request('status')==='graduated'?'selected':'' }}>Graduated</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Gender</label>
            <select name="gender" class="form-input">
                <option value="">All Genders</option>
                <option value="male" {{ request('gender')==='male'?'selected':'' }}>Male</option>
                <option value="female" {{ request('gender')==='female'?'selected':'' }}>Female</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">Filter</button>
        <a href="{{ route('students.index') }}" class="btn-secondary">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 dark:border-gray-700">
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Student</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">ID / Course</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Room</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr class="table-row">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($student->photo)
                            <img src="{{ $student->photo_url }}" class="w-9 h-9 rounded-xl object-cover">
                        @else
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white" style="background:linear-gradient(135deg,#004d98,#a50044)">
                                {{ strtoupper(substr($student->full_name,0,2)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-semibold text-gray-800 dark:text-white">{{ $student->full_name }}</div>
                            <div class="text-xs text-gray-400">{{ $student->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 hidden md:table-cell">
                    <div class="font-medium text-gray-700 dark:text-gray-300">{{ $student->student_id }}</div>
                    <div class="text-xs text-gray-400">{{ $student->course ?? '—' }} {{ $student->year_level ? '· Yr '.$student->year_level : '' }}</div>
                </td>
                <td class="px-6 py-4 hidden lg:table-cell text-gray-600 dark:text-gray-300">
                    @if($student->activeAllocation)
                        Room {{ $student->activeAllocation->room->room_number }}<br>
                        <span class="text-xs text-gray-400">{{ $student->activeAllocation->room->building->name }}</span>
                    @else
                        <span class="text-gray-400 text-xs">Not allocated</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="badge {{ match($student->status) { 'active'=>'badge-green', 'inactive'=>'badge-red', 'graduated'=>'badge-blue' } }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a>
                        <a href="{{ route('students.edit', $student) }}" class="text-gray-600 hover:text-gray-800 text-xs font-medium">Edit</a>
                        <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($students->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection