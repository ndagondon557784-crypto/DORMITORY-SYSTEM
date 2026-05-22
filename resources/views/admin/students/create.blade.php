@extends('layouts.app')
@section('title','Add Student')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6"><a href="{{ route('admin.students.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a><h1 class="text-2xl font-extrabold text-gray-800 mt-2">Add New Student</h1></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-5">
            @csrf
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-1 border-b border-gray-100">Account Info</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 {{ $errors->has('name')?'border-red-400':'border-gray-200' }}">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div class="col-span-2"><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 {{ $errors->has('email')?'border-red-400':'border-gray-200' }}">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Password *</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Confirm Password *</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider pb-1 border-b border-gray-100 pt-2">Student Info</p>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Student Number *</label>
                <input type="text" name="student_number" value="{{ old('student_number') }}" required placeholder="2024-0001" class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 {{ $errors->has('student_number')?'border-red-400':'border-gray-200' }}">
                @error('student_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Gender *</label>
                <select name="gender" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                    @foreach(['male','female','other'] as $g)<option value="{{ $g }}" {{ old('gender')==$g?'selected':'' }}>{{ ucfirst($g) }}</option>@endforeach
                </select></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Course *</label>
                <input type="text" name="course" value="{{ old('course') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Year Level *</label>
                <select name="year_level" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                    @foreach(range(1,6) as $y)<option value="{{ $y }}" {{ old('year_level')==$y?'selected':'' }}>Year {{ $y }}</option>@endforeach
                </select></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Emergency Contact</label>
                <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div class="col-span-2"><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Home Address</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">{{ old('address') }}</textarea></div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">Create Student</button>
                <a href="{{ route('admin.students.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection