@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('student.portal') }}" class="text-[#004D98] text-sm hover:underline">← Back to Profile</a>
        <h1 class="text-2xl font-extrabold text-gray-800 mt-2">Edit My Profile</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-gray-100">
                Account Information
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Full Name *
                    </label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Email Address *
                    </label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                  {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Course *</label>
                    <input type="text" name="course" value="{{ old('course', $student->course) }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white">
                    @error('course')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Year Level *</label>
                    <select name="year_level"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                        @foreach(range(1,6) as $y)
                        <option value="{{ $y }}" {{ old('year_level',$student->year_level)==$y?'selected':'' }}>
                            Year {{ $y }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Emergency Contact</label>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $student->emergency_contact) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Home Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white resize-none">{{ old('address', $student->address) }}</textarea>
                </div>
            </div>

            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-2 border-b border-gray-100 pt-3">
                Change Password
                <span class="font-normal text-gray-400 normal-case">(leave blank to keep current)</span>
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Current Password</label>
                    <input type="password" name="current_password"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                  {{ $errors->has('current_password') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">New Password</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">
                    Save Changes
                </button>
                <a href="{{ route('student.portal') }}"
                   class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection