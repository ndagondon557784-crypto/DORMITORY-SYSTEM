@extends('layouts.app')
@section('title','Add Admin')
@section('content')
<div class="max-w-lg">
    <div class="mb-6"><a href="{{ route('admin.admins.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a><h1 class="text-2xl font-extrabold text-gray-800 mt-2">Add Administrator</h1></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-5">
            @csrf
            <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Full Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Password *</label>
            <input type="password" name="password" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Confirm Password *</label>
            <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">Create Admin</button>
                <a href="{{ route('admin.admins.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection