@extends('layouts.app')
@section('title', 'Edit Student')
@section('content')

<div class="page-header">
    <div>
        <a href="{{ route('students.show', $student) }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-1 text-sm mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
        <h2 class="font-display text-2xl font-700 text-slate-900">Edit Student — {{ $student->full_name }}</h2>
    </div>
</div>

<form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-5">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Student ID *</label>
                        <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" class="form-input @error('student_id') border-red-400 @enderror" required>
                        @error('student_id')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-input @error('email') border-red-400 @enderror" required>
                        @error('email')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Phone *</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Gender *</label>
                        <select name="gender" class="form-input" required>
                            @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" {{ old('gender', $student->gender) == $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Date of Birth *</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            @foreach(['active','inactive','graduated','suspended'] as $s)
                            <option value="{{ $s }}" {{ old('status', $student->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-input" rows="2" required>{{ old('address', $student->address) }}</textarea>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-5">Academic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Course *</label>
                        <input type="text" name="course" value="{{ old('course', $student->course) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Year Level *</label>
                        <select name="year_level" class="form-input" required>
                            @for($i=1;$i<=6;$i++)
                            <option value="{{ $i }}" {{ old('year_level', $student->year_level) == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-5">Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Guardian Name *</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Guardian Phone *</label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Relationship *</label>
                        <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $student->emergency_contact) }}" class="form-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-4">Student Photo</h3>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-slate-200" id="photoPreview">
                        <img src="{{ $student->photo_url }}" alt="{{ $student->full_name }}" class="w-full h-full object-cover">
                    </div>
                    <label class="btn btn-secondary btn-sm cursor-pointer">
                        <i data-lucide="upload" class="w-3 h-3"></i>
                        Change Photo
                        <input type="file" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    </label>
                </div>
            </div>
            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-4">Medical Notes</h3>
                <textarea name="medical_notes" class="form-input" rows="4" placeholder="Any known conditions...">{{ old('medical_notes', $student->medical_notes) }}</textarea>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('students.show', $student) }}" class="btn btn-secondary flex-1 justify-center">Cancel</a>
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <i data-lucide="save" class="w-4 h-4"></i> Update
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection