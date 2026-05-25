@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb', 'Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="h-24" style="background:linear-gradient(135deg,#004d98,#a50044)"></div>
        <div class="px-6 pb-6 -mt-10">
            <div class="flex items-end gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl ring-4 ring-white dark:ring-gray-800 flex items-center justify-center text-2xl font-extrabold text-white shadow-xl flex-shrink-0" style="background:var(--barca-maroon)">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div class="pb-2">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->role?->display_name }}</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')

                @if(session('success'))
                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">{{ session('success') }}</div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">New Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Avatar</label>
                        <input type="file" name="avatar" accept="image/*" class="form-input">
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection