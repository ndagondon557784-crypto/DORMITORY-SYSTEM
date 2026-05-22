@extends('layouts.app')
@section('title','Edit Room')
@section('content')
<div class="max-w-2xl">
    <div class="mb-6"><a href="{{ route('admin.rooms.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a><h1 class="text-2xl font-extrabold text-gray-800 mt-2">Edit Room {{ $room->room_number }}</h1></div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.rooms.update',$room) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-5">
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Room Number *</label>
                <input type="text" name="room_number" value="{{ old('room_number',$room->room_number) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Capacity *</label>
                <input type="number" name="capacity" value="{{ old('capacity',$room->capacity) }}" min="1" max="30" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Type *</label>
                <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                    @foreach(['single','double','triple','dormitory'] as $t)<option value="{{ $t }}" {{ old('type',$room->type)==$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach
                </select></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Gender *</label>
                <select name="gender" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                    @foreach(['male','female','mixed'] as $g)<option value="{{ $g }}" {{ old('gender',$room->gender)==$g?'selected':'' }}>{{ ucfirst($g) }}</option>@endforeach
                </select></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Building</label>
                <input type="text" name="building" value="{{ old('building',$room->building) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Floor</label>
                <input type="text" name="floor" value="{{ old('floor',$room->floor) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Price/Month (₱) *</label>
                <input type="number" name="price_per_month" value="{{ old('price_per_month',$room->price_per_month) }}" step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50"></div>
                <div><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Status *</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                    @foreach(['available','full','maintenance'] as $s)<option value="{{ $s }}" {{ old('status',$room->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach
                </select></div>
                <div class="col-span-2"><label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">{{ old('description',$room->description) }}</textarea></div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm">Save Changes</button>
                <a href="{{ route('admin.rooms.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection