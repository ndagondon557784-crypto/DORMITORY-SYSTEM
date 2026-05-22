@extends('layouts.app')
@section('title', 'Add Student')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.students.index') }}">Students</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Add Student</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Student</h1>
    </div>
    <a href="{{ route('admin.students.index') }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Info -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="user" class="w-4 h-4 text-primary-500"></i> Personal Information
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-input @error('first_name') border-red-400 @enderror" placeholder="Juan">
                        @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-input @error('last_name') border-red-400 @enderror" placeholder="dela Cruz">
                        @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" placeholder="juan@example.com">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input @error('phone') border-red-400 @enderror" placeholder="+63 912 345 6789">
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-input @error('date_of_birth') border-red-400 @enderror">
                        @error('date_of_birth') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="form-input @error('gender') border-red-400 @enderror">
                            <option value="">Select gender</option>
                            @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                        @error('gender') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Home Address <span class="text-red-500">*</span></label>
                        <textarea name="home_address" rows="2" class="form-input @error('home_address') border-red-400 @enderror" placeholder="Complete home address">{{ old('home_address') }}</textarea>
                        @error('home_address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="book-open" class="w-4 h-4 text-primary-500"></i> Academic Information
                </h3>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Student ID <span class="text-red-500">*</span></label>
                        <input type="text" name="student_id" value="{{ old('student_id') }}" class="form-input font-mono @error('student_id') border-red-400 @enderror" placeholder="2024-00001">
                        @error('student_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Course <span class="text-red-500">*</span></label>
                        <input type="text" name="course" value="{{ old('course') }}" class="form-input @error('course') border-red-400 @enderror" placeholder="BS Computer Science">
                        @error('course') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Year Level <span class="text-red-500">*</span></label>
                        <select name="year_level" class="form-input @error('year_level') border-red-400 @enderror">
                            <option value="">Select year</option>
                            @foreach(range(1,6) as $y)
                            <option value="{{ $y }}" {{ old('year_level') == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                            @endforeach
                        </select>
                        @error('year_level') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="phone-call" class="w-4 h-4 text-primary-500"></i> Emergency Contact
                </h3>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Contact Name <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="form-input @error('emergency_contact_name') border-red-400 @enderror" placeholder="Full name">
                        @error('emergency_contact_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Contact Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="form-input @error('emergency_contact_phone') border-red-400 @enderror" placeholder="+63 912 345 6789">
                        @error('emergency_contact_phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Relation <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" class="form-input @error('emergency_contact_relation') border-red-400 @enderror" placeholder="Parent, Sibling...">
                        @error('emergency_contact_relation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Photo Upload -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="image" class="w-4 h-4 text-primary-500"></i> Profile Photo
                </h3>
                <div x-data="{ preview: null }" class="text-center">
                    <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 mb-4">
                        <img x-show="preview" :src="preview" class="w-full h-full object-cover">
                        <div x-show="!preview" class="w-full h-full flex items-center justify-center">
                            <i data-feather="user" class="w-8 h-8 text-gray-400"></i>
                        </div>
                    </div>
                    <label class="btn-secondary cursor-pointer text-xs">
                        <i data-feather="upload" class="w-3 h-3"></i> Upload Photo
                        <input type="file" name="avatar" accept="image/*" class="hidden"
                               @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">JPG, PNG max 2MB</p>
                </div>
            </div>

            <!-- Status & Notes -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="settings" class="w-4 h-4 text-primary-500"></i> Settings
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="form-input @error('status') border-red-400 @enderror">
                            @foreach(['active','inactive','graduated','suspended'] as $s)
                            <option value="{{ $s }}" {{ old('status','active') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="4" class="form-input" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center">
                    <i data-feather="save" class="w-4 h-4"></i> Save Student
                </button>
                <a href="{{ route('admin.students.index') }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection