@extends('layouts.app')
@section('title', 'Edit Dormitory')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.dormitories.index') }}">Dormitories</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Edit {{ $dormitory->name }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Dormitory</h1>
    </div>
    <a href="{{ route('admin.dormitories.show', $dormitory) }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.dormitories.update', $dormitory) }}" method="POST">
    @csrf @method('PUT')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-feather="home" class="w-4 h-4 text-primary-500"></i> Dormitory Information
            </h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Dormitory Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $dormitory->name) }}" class="form-input @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $dormitory->code) }}" class="form-input font-mono @error('code') border-red-400 @enderror" oninput="this.value = this.value.toUpperCase()">
                    @error('code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Contact Number <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $dormitory->contact_number) }}" class="form-input @error('contact_number') border-red-400 @enderror">
                    @error('contact_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $dormitory->email) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Total Capacity <span class="text-red-500">*</span></label>
                    <input type="number" name="total_capacity" value="{{ old('total_capacity', $dormitory->total_capacity) }}" min="1" class="form-input">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" class="form-input @error('address') border-red-400 @enderror">{{ old('address', $dormitory->address) }}</textarea>
                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $dormitory->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Settings</h3>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $dormitory->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-600 peer-checked:bg-primary-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active Dormitory</span>
                </label>
            </div>
            <div class="card p-4 bg-gray-50 dark:bg-gray-700/50">
                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Quick Stats</h4>
                <dl class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Total Rooms</dt>
                        <dd class="font-medium">{{ $dormitory->rooms_count }}</dd>
                    </div>
                </dl>
            </div>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="save" class="w-4 h-4"></i> Update Dormitory
                </button>
                <a href="{{ route('admin.dormitories.show', $dormitory) }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection