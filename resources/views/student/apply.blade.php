@extends('layouts.app')
@section('title', 'Apply for Room')
@section('content')

<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-[#004D98] text-sm hover:underline">← Dashboard</a>
        <h1 class="text-2xl font-extrabold text-gray-800 mt-2">Apply for a Room</h1>
        <p class="text-gray-400 text-sm mt-1">Browse available rooms and submit your application</p>
    </div>

    @if($rooms->isEmpty())
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
        <div class="text-5xl mb-3">😔</div>
        <h2 class="font-extrabold text-yellow-800 text-lg mb-2">No Rooms Available</h2>
        <p class="text-yellow-700 text-sm">
            There are currently no available rooms matching your profile.
            Please contact the dormitory administrator.
        </p>
    </div>
    @else
    <form method="POST" action="{{ route('student.apply.store') }}" class="space-y-5">
        @csrf
        <div class="space-y-4">
            @foreach($rooms as $room)
            @php $pct = $room->capacity > 0 ? round(($room->occupied/$room->capacity)*100) : 0; @endphp
            <label class="block cursor-pointer group">
                <input type="radio" name="room_id" value="{{ $room->id }}" class="peer hidden"
                       {{ old('room_id')==$room->id ? 'checked' : '' }} required>
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-5 transition-all
                            peer-checked:border-[#004D98] peer-checked:bg-blue-50
                            group-hover:border-blue-300 group-hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center flex-wrap gap-2 mb-2">
                                <span class="text-xl font-black text-[#004D98]">Room {{ $room->room_number }}</span>
                                <span class="px-2 py-0.5 bg-[#EDBB00] text-[#004D98] text-xs font-bold rounded-full capitalize">{{ $room->type }}</span>
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full capitalize">{{ $room->gender }}</span>
                            </div>
                            <p class="text-gray-500 text-sm mb-2">{{ $room->building }} · Floor {{ $room->floor }}</p>
                            @if($room->description)
                            <p class="text-gray-600 text-sm">{{ $room->description }}</p>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-2xl font-black text-[#A50044]">₱{{ number_format($room->price_per_month,0) }}</p>
                            <p class="text-gray-400 text-xs">/month</p>
                            <p class="text-gray-500 text-xs mt-1">{{ $room->occupied }}/{{ $room->capacity }} occupied</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all"
                                 style="width:{{ $pct }}%; background:{{ $pct>=100?'#A50044':($pct>=70?'#EDBB00':'#004D98') }}">
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ $room->available_slots }} bed(s) available</p>
                    </div>
                </div>
            </label>
            @endforeach
        </div>

        @error('room_id')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                Reason for Application <span class="text-gray-400 font-normal normal-case">(optional)</span>
            </label>
            <textarea name="reason" rows="3"
                      placeholder="Tell us why you'd like this room..."
                      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white resize-none">{{ old('reason') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="px-8 py-3 bg-[#A50044] text-white font-extrabold rounded-xl hover:bg-[#7A003C] transition text-sm shadow-lg active:scale-[.98]">
                Submit Application
            </button>
            <a href="{{ route('dashboard') }}"
               class="px-8 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition text-sm font-semibold">
                Cancel
            </a>
        </div>
    </form>
    @endif
</div>

@endsection