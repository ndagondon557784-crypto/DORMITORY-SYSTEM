@extends('layouts.app')
@section('title', 'Edit Allocation')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.allocations.index') }}">Allocations</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Edit #{{ $allocation->id }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Allocation #{{ $allocation->id }}</h1>
    </div>
    <a href="{{ route('admin.allocations.show', $allocation) }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.allocations.update', $allocation) }}" method="POST">
    @csrf @method('PUT')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Read-only info -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Allocation Info (Read-only)</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <img src="{{ $allocation->student->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-medium text-sm">{{ $allocation->student->full_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $allocation->student->student_id }}</p>
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <p class="font-medium text-sm">Room {{ $allocation->room->room_number }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $allocation->room->dormitory->name }}</p>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="calendar" class="w-4 h-4 text-primary-500"></i> Editable Fields
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Check-in Date <span class="text-red-500">*</span></label>
                        <input type="date" name="check_in_date" value="{{ old('check_in_date', $allocation->check_in_date->format('Y-m-d')) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Expected Check-out</label>
                        <input type="date" name="expected_check_out_date" value="{{ old('expected_check_out_date', $allocation->expected_check_out_date?->format('Y-m-d')) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Deposit Amount (₱)</label>
                        <input type="number" name="deposit_amount" value="{{ old('deposit_amount', $allocation->deposit_amount) }}" step="0.01" min="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="form-input">
                            @foreach(['active','checked_out','cancelled','expired'] as $s)
                            <option value="{{ $s }}" {{ old('status', $allocation->status) == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" class="form-input">{{ old('notes', $allocation->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6 bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800">
                <h3 class="font-semibold text-yellow-900 dark:text-yellow-300 mb-2 flex items-center gap-2">
                    <i data-feather="alert-triangle" class="w-4 h-4"></i> Warning
                </h3>
                <p class="text-xs text-yellow-800 dark:text-yellow-300">
                    Changing the status to <strong>Checked Out</strong> or <strong>Cancelled</strong> will automatically update the room's occupancy and free up the slot.
                </p>
            </div>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="save" class="w-4 h-4"></i> Update Allocation
                </button>
                <a href="{{ route('admin.allocations.show', $allocation) }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection