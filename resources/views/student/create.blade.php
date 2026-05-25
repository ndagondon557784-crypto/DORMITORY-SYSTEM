@extends('layouts.app')
@section('title', 'Add Student')
@section('page-title', 'Add Student')
@section('breadcrumb', 'Students / Add New')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700" style="background:linear-gradient(135deg,#004d98,#a50044)">
            <h2 class="text-lg font-bold text-white">New Student Registration</h2>
            <p class="text-white/60 text-sm mt-0.5">Fill in all required fields</p>
        </div>

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $err)<li>• {{ $err }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Student ID *</label>
                    <input type="text" name="student_id" value="{{ old('student_id') }}" class="form-input" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-input" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Gender *</label>
                    <select name="gender" class="form-input" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender')==='male'?'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender')==='female'?'selected':'' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Course</label>
                    <input type="text" name="course" value="{{ old('course') }}" class="form-input" placeholder="e.g., BS Computer Science">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Year Level</label>
                    <select name="year_level" class="form-input">
                        <option value="">Select Year</option>
                        @foreach(['1st','2nd','3rd','4th','5th'] as $yr)
                        <option value="{{ $yr }}" {{ old('year_level')===$yr?'selected':'' }}>{{ $yr }} Year</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Address</label>
                    <textarea name="address" rows="2" class="form-input">{{ old('address') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Emergency Contact Name</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="form-input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Emergency Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="form-input">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Status *</label>
                    <select name="status" class="form-input" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Photo</label>
                    <input type="file" name="photo" accept="image/*" class="form-input">
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit" class="btn-primary">Save Student</button>
                <a href="{{ route('students.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection