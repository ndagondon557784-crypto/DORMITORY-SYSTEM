@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Profile</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your personal information and password</p>
    </div>
</div>

<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" x-data="profileForm()">
    @csrf
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Avatar card -->
        <div class="space-y-6">
            <div class="card p-6 text-center">
                <div class="relative w-28 h-28 mx-auto mb-4">
                    <img :src="preview" class="w-28 h-28 rounded-full object-cover ring-4 ring-primary-100 dark:ring-primary-900">
                    <label class="absolute bottom-0 right-0 w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-primary-700 transition-colors shadow-lg">
                        <i data-feather="camera" class="w-4 h-4 text-white"></i>
                        <input type="file" name="avatar" accept="image/*" class="hidden"
                               @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>
                </div>
                <h2 class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <span class="{{ $user->role === 'admin' ? 'badge-danger' : 'badge-info' }} capitalize mt-2 inline-block">{{ $user->role }}</span>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-3">JPG, PNG max 2MB</p>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Account Stats</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Allocations done</dt>
                        <dd class="font-medium">{{ $user->allocations()->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Payments recorded</dt>
                        <dd class="font-medium">{{ $user->payments()->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Member since</dt>
                        <dd class="font-medium">{{ $user->created_at->format('M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Last login</dt>
                        <dd class="font-medium">{{ $user->last_login_at?->diffForHumans() ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Form -->
        <div class="lg:col-span-2 space-y-6">
            @if(session('success'))
            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center gap-3">
                <i data-feather="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0"></i>
                <p class="text-sm text-green-800 dark:text-green-300">{{ session('success') }}</p>
            </div>
            @endif

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="user" class="w-4 h-4 text-primary-500"></i> Personal Information
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
                    <div class="sm:col-span-2">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="+63 912 345 6789">
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                    <i data-feather="lock" class="w-4 h-4 text-primary-500"></i> Change Password
                </h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Leave blank to keep your current password.</p>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div x-data="{ show: false }">
                        <label class="form-label">Current Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="current_password" class="form-input pr-10 @error('current_password') border-red-400 @enderror" placeholder="••••••••">
                            <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i :data-feather="show ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('current_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div x-data="{ show: false }">
                        <label class="form-label">New Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" class="form-input pr-10 @error('password') border-red-400 @enderror" placeholder="Min. 8 chars">
                            <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i :data-feather="show ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div x-data="{ show: false }">
                        <label class="form-label">Confirm New Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" class="form-input pr-10" placeholder="Repeat">
                            <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i :data-feather="show ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="reset" class="btn-secondary" @click="preview = '{{ $user->avatar_url }}'">Reset</button>
                <button type="submit" class="btn-primary">
                    <i data-feather="save" class="w-4 h-4"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function profileForm() {
    return { preview: '{{ $user->avatar_url }}' };
}
feather.replace();
</script>
@endpush