@extends('layouts.app')
@section('title', 'Edit Room '.$room->room_number)
@section('content')

<div class="page-header">
    <div>
        <a href="{{ route('rooms.show', $room) }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-1 text-sm mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
        <h2 class="font-display text-2xl font-700 text-slate-900">Edit Room {{ $room->room_number }}</h2>
    </div>
</div>

<form method="POST" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-5">Room Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Room Number *</label>
                        <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}" class="form-input @error('room_number') border-red-400 @enderror" required>
                        @error('room_number')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">Building *</label>
                        <input type="text" name="building" value="{{ old('building', $room->building) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Floor *</label>
                        <input type="number" name="floor" value="{{ old('floor', $room->floor) }}" class="form-input" min="1" required>
                    </div>
                    <div>
                        <label class="form-label">Room Type *</label>
                        <select name="type" class="form-input" required>
                            @foreach(['single','double','triple','quad','suite'] as $t)
                            <option value="{{ $t }}" {{ old('type', $room->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Capacity *</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" class="form-input" min="1" max="20" required>
                    </div>
                    <div>
                        <label class="form-label">Monthly Rate (₱) *</label>
                        <input type="number" name="monthly_rate" value="{{ old('monthly_rate', $room->monthly_rate) }}" class="form-input" min="0" step="0.01" required>
                    </div>
                    <div>
                        <label class="form-label">Gender Type *</label>
                        <select name="gender_type" class="form-input" required>
                            @foreach(['male','female','mixed'] as $g)
                            <option value="{{ $g }}" {{ old('gender_type', $room->gender_type) == $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input" required>
                            @foreach(['available','occupied','maintenance','reserved'] as $s)
                            <option value="{{ $s }}" {{ old('status', $room->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3">{{ old('description', $room->description) }}</textarea>
                </div>
                <div class="mt-4">
                    <label class="form-label">Amenities</label>
                    <textarea name="amenities" class="form-input" rows="2">{{ old('amenities', $room->amenities) }}</textarea>
                </div>
            </div>
            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-5">Room Features</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php $features = [
                        ['name'=>'has_aircon','label'=>'Air Conditioning','icon'=>'wind'],
                        ['name'=>'has_wifi','label'=>'WiFi','icon'=>'wifi'],
                        ['name'=>'has_bathroom','label'=>'Private Bathroom','icon'=>'bath'],
                        ['name'=>'has_study_desk','label'=>'Study Desk','icon'=>'book-open'],
                    ] @endphp
                    @foreach($features as $feature)
                    @php $checked = old($feature['name'], $room->{$feature['name']}); @endphp
                    <label class="flex flex-col items-center gap-3 p-4 border-2 rounded-2xl cursor-pointer hover:border-indigo-300 transition-colors {{ $checked ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200' }}" id="label_{{ $feature['name'] }}">
                        <input type="checkbox" name="{{ $feature['name'] }}" value="1" class="sr-only" id="{{ $feature['name'] }}" {{ $checked ? 'checked' : '' }} onchange="toggleFeatureLabel(this)">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $checked ? 'bg-indigo-100' : 'bg-slate-100' }}" id="icon_{{ $feature['name'] }}">
                            <i data-lucide="{{ $feature['icon'] }}" class="w-5 h-5 {{ $checked ? 'text-indigo-600' : 'text-slate-500' }}"></i>
                        </div>
                        <span class="text-xs font-600 text-center text-slate-600">{{ $feature['label'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-display font-700 text-slate-900 mb-4">Room Photo</h3>
                <div class="flex flex-col items-center gap-3">
                    <div class="w-full h-40 rounded-2xl overflow-hidden border-2 border-slate-200" id="photoPreview">
                        @if($room->photo)
                        <img src="{{ asset('storage/'.$room->photo) }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                            <i data-lucide="image" class="w-10 h-10"></i>
                        </div>
                        @endif
                    </div>
                    <label class="btn btn-secondary w-full justify-center cursor-pointer">
                        <i data-lucide="upload" class="w-4 h-4"></i> Change Photo
                        <input type="file" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    </label>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary flex-1 justify-center">Cancel</a>
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
        reader.readAsDataURL(input.files[