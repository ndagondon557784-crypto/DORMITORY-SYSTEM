@extends('layouts.app')
@section('title', 'Add Dormitory')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.dormitories.index') }}">Dormitories</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Add</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Dormitory</h1>
    </div>
    <a href="{{ route('admin.dormitories.index') }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.dormitories.store') }}" method="POST">
    @csrf
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-feather="home" class="w-4 h-4 text-primary-500"></i> Dormitory Information
            </h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Dormitory Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror" placeholder="Main Dormitory">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" class="form-input font-mono @error('code') border-red-400 @enderror" placeholder="DORM-A" oninput="this.value = this.value.toUpperCase()">
                    @error('code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Contact Number <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" class="form-input @error('contact_number') border-red-400 @enderror" placeholder="+63 82 123 4567">
                    @error('contact_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" placeholder="dorm@school.edu.ph">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Total Capacity <span class="text-red-500">*</span></label>
                    <input type="number" name="total_capacity" value="{{ old('total_capacity', 50) }}" min="1" class="form-input @error('total_capacity') border-red-400 @enderror">
                    @error('total_capacity')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" class="form-input @error('address') border-red-400 @enderror" placeholder="Complete address">{{ old('address') }}</textarea>
                    @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Brief description...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Settings</h3>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-600 peer-checked:bg-primary-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active Dormitory</span>
                </label>
            </div>
            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="save" class="w-4 h-4"></i> Save Dormitory
                </button>
                <a href="{{ route('admin.dormitories.index') }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection