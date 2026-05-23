@extends('layouts.app')
@section('title', 'Add User')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.users.index') }}">Users</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Add User</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New User</h1>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="user" class="w-4 h-4 text-primary-500"></i> User Information
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror" placeholder="Juan dela Cruz">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" placeholder="user@dormms.ph">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+63 912 345 6789">
                    </div>
                    <div>
                        <label class="form-label">Role <span class="text-red-500">*</span></label>
                        <select name="role" class="form-input @error('role') border-red-400 @enderror">
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="lock" class="w-4 h-4 text-primary-500"></i> Password
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div x-data="{ show: false }">
                        <label class="form-label">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" class="form-input pr-10 @error('password') border-red-400 @enderror" placeholder="Min. 8 characters">
                            <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i :data-feather="show ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div x-data="{ show: false }">
                        <label class="form-label">Confirm Password <span class="text-red-500">*</span></label>
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
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="shield" class="w-4 h-4 text-primary-500"></i> Account Settings
                </h3>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-600 peer-checked:bg-primary-600 rounded-full transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Account Active</span>
                    </label>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Inactive users cannot log in.</p>
                </div>
            </div>

            <div class="card p-6 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2 flex items-center gap-2">
                    <i data-feather="info" class="w-4 h-4"></i> Role Permissions
                </h3>
                <ul class="space-y-1 text-xs text-blue-800 dark:text-blue-300">
                    <li><span class="font-bold">Admin:</span> Full access, can manage users</li>
                    <li><span class="font-bold">Staff:</span> Can manage students, rooms, allocations, payments</li>
                </ul>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="user-plus" class="w-4 h-4"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')<script>feather.replace();</script>@endpush