@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.users.index') }}">Users</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Edit {{ $user->name }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf @method('PUT')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="user" class="w-4 h-4 text-primary-500"></i> User Information
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Role <span class="text-red-500">*</span></label>
                        <select name="role" class="form-input">
                            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <i data-feather="lock" class="w-4 h-4 text-primary-500"></i> Change Password
                </h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Leave blank to keep the current password.</p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div x-data="{ show: false }">
                        <label class="form-label">New Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" class="form-input pr-10" placeholder="Min. 8 characters">
                            <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i :data-feather="show ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div x-data="{ show: false }">
                        <label class="form-label">Confirm New Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" class="form-input pr-10" placeholder="Repeat password">
                            <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i :data-feather="show ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6 text-center">
                <img src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-3 ring-4 ring-primary-100 dark:ring-primary-900">
                <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize mt-0.5">{{ $user->role }}</p>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Account Settings</h3>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-600 peer-checked:bg-primary-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Account Active</span>
                </label>
            </div>

            @if($user->id === auth()->id())
            <div class="card p-4 bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800">
                <p class="text-xs text-yellow-800 dark:text-yellow-300 flex items-center gap-2">
                    <i data-feather="alert-triangle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                    You are editing your own account.
                </p>
            </div>
            @endif

            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="save" class="w-4 h-4"></i> Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')<script>feather.replace();</script>@endpush