@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User Account')
@section('breadcrumb', 'Admin / Users / Edit')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Header --}}
        <div class="px-7 py-6" style="background: linear-gradient(135deg, #0a1628 0%, #004d98 60%, #a50044 100%);">
            <h2 class="text-xl font-bold text-white">Edit User Account</h2>
            <p class="text-white/55 text-sm mt-1">
                Updating account for <strong class="text-yellow-300">{{ $user->name }}</strong>.
                Leave password blank to keep the current one.
            </p>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="px-7 py-6" x-data="{ showPw: false, showPw2: false }">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="mb-6 flex gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-1">Please fix the following errors:</p>
                    <ul class="text-sm text-red-600 dark:text-red-300 space-y-0.5 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="space-y-5">

                {{-- Full Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                        class="form-input @error('name') ring-2 ring-red-400 border-red-400 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="email"
                        class="form-input @error('email') ring-2 ring-red-400 border-red-400 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">
                        Phone Number
                    </label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        autocomplete="tel"
                        class="form-input @error('phone') ring-2 ring-red-400 border-red-400 @enderror"
                    >
                    @error('phone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label for="role_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="role_id"
                        name="role_id"
                        required
                        class="form-input @error('role_id') ring-2 ring-red-400 border-red-400 @enderror"
                    >
                        <option value="">— Select a role —</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password section --}}
                <div class="border-t border-dashed border-gray-200 dark:border-gray-600 pt-2">
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4">
                        Change Password <span class="normal-case font-normal text-gray-400">(leave blank to keep current)</span>
                    </p>
                </div>

                {{-- New Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">
                        New Password
                    </label>
                    <div class="relative">
                        <input
                            :type="showPw ? 'text' : 'password'"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Leave blank to keep current password"
                            class="form-input pr-11 @error('password') ring-2 ring-red-400 border-red-400 @enderror"
                        >
                        <button type="button" @click="showPw = !showPw"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" tabindex="-1">
                            <svg x-show="!showPw" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPw" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm new password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input
                            :type="showPw2 ? 'text' : 'password'"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Re-enter new password"
                            class="form-input pr-11"
                        >
                        <button type="button" @click="showPw2 = !showPw2"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" tabindex="-1">
                            <svg x-show="!showPw2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="showPw2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Active toggle --}}
                <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center h-5 mt-0.5">
                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label for="is_active" class="text-sm font-semibold text-gray-700 dark:text-gray-200 cursor-pointer">
                            Account Active
                        </label>
                        <p class="text-xs text-gray-400 mt-0.5">Inactive accounts cannot log in to the system.</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-7 pt-5 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection