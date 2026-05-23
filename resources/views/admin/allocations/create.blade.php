@extends('layouts.app')
@section('title', 'New Allocation')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.allocations.index') }}">Allocations</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>New Allocation</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">New Room Allocation</h1>
    </div>
    <a href="{{ route('admin.allocations.index') }}" class="btn-secondary">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Back
    </a>
</div>

<form action="{{ route('admin.allocations.store') }}" method="POST" x-data="allocationForm()">
    @csrf
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Selection -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="user" class="w-4 h-4 text-primary-500"></i> Select Student
                </h3>
                <div>
                    <label class="form-label">Student <span class="text-red-500">*</span></label>
                    <select name="student_id" x-model="studentId" @change="loadStudent()" class="form-input @error('student_id') border-red-400 @enderror">
                        <option value="">Choose a student...</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                            {{ $student->full_name }} ({{ $student->student_id }})
                        </option>
                        @endforeach
                    </select>
                    @error('student_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Student Preview -->
                <div x-show="student" x-cloak class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl flex items-center gap-4">
                    <img :src="student?.avatar_url ?? ''" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white" x-text="student?.full_name"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="student?.course + ' · Year ' + student?.year_level"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="student?.email"></p>
                    </div>
                </div>
            </div>

            <!-- Room Selection -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="home" class="w-4 h-4 text-primary-500"></i> Select Room
                </h3>
                <div>
                    <label class="form-label">Available Room <span class="text-red-500">*</span></label>
                    <select name="room_id" x-model="roomId" @change="loadRoom()" class="form-input @error('room_id') border-red-400 @enderror">
                        <option value="">Choose a room...</option>
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id', request('room_id')) == $room->id ? 'selected' : '' }}>
                            {{ $room->dormitory->name }} — Room {{ $room->room_number }} ({{ $room->available_slots }} slot/s left · ₱{{ number_format($room->monthly_rate, 0) }}/mo)
                        </option>
                        @endforeach
                    </select>
                    @error('room_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Room Preview -->
                <div x-show="room" x-cloak class="mt-4 grid grid-cols-3 gap-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Type</p>
                        <p class="font-semibold text-gray-900 dark:text-white capitalize" x-text="room?.room_type"></p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Available</p>
                        <p class="font-semibold text-green-600" x-text="room?.available_slots + ' slot(s)'"></p>
                    </div>
                    <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-xl text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Monthly Rate</p>
                        <p class="font-bold text-primary-600 dark:text-primary-400" x-text="'₱' + parseFloat(room?.monthly_rate || 0).toLocaleString()"></p>
                    </div>
                </div>
            </div>

            <!-- Dates & Details -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-feather="calendar" class="w-4 h-4 text-primary-500"></i> Allocation Details
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Check-in Date <span class="text-red-500">*</span></label>
                        <input type="date" name="check_in_date" value="{{ old('check_in_date', date('Y-m-d')) }}" class="form-input @error('check_in_date') border-red-400 @enderror">
                        @error('check_in_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Expected Check-out Date</label>
                        <input type="date" name="expected_check_out_date" value="{{ old('expected_check_out_date') }}" class="form-input @error('expected_check_out_date') border-red-400 @enderror">
                        @error('expected_check_out_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Deposit Amount (₱)</label>
                        <input type="number" name="deposit_amount" value="{{ old('deposit_amount', 0) }}" step="0.01" min="0" class="form-input @error('deposit_amount') border-red-400 @enderror">
                        @error('deposit_amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" class="form-input" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="space-y-6">
            <div class="card p-6 bg-primary-50 dark:bg-primary-900/20 border-primary-200 dark:border-primary-800">
                <h3 class="font-semibold text-primary-900 dark:text-primary-300 mb-4 flex items-center gap-2">
                    <i data-feather="clipboard" class="w-4 h-4"></i> Allocation Summary
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Student</span>
                        <span class="font-medium text-gray-900 dark:text-white" x-text="student?.full_name ?? '—'">—</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Room</span>
                        <span class="font-medium text-gray-900 dark:text-white" x-text="room ? 'Room ' + room.room_number : '—'">—</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Monthly Rate</span>
                        <span class="font-bold text-primary-700 dark:text-primary-400" x-text="room ? '₱' + parseFloat(room.monthly_rate).toLocaleString() : '—'">—</span>
                    </div>
                    <div class="h-px bg-primary-200 dark:bg-primary-700"></div>
                    <p class="text-xs text-primary-700 dark:text-primary-400">
                        Payments are tracked separately after allocation is confirmed.
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="btn-primary justify-center" :disabled="!studentId || !roomId">
                    <i data-feather="check-circle" class="w-4 h-4"></i> Confirm Allocation
                </button>
                <a href="{{ route('admin.allocations.index') }}" class="btn-secondary justify-center">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function allocationForm() {
    return {
        studentId: '{{ old('student_id', request('student_id')) }}',
        roomId: '{{ old('room_id', request('room_id')) }}',
        student: null,
        room: null,

        students: @json($students->map(fn($s) => ['id' => $s->id, 'full_name' => $s->full_name, 'student_id' => $s->student_id, 'course' => $s->course, 'year_level' => $s->year_level, 'email' => $s->email, 'avatar_url' => $s->avatar_url])),
        rooms: @json($rooms->map(fn($r) => ['id' => $r->id, 'room_number' => $r->room_number, 'room_type' => $r->room_type, 'available_slots' => $r->available_slots, 'monthly_rate' => $r->monthly_rate])),

        init() {
            if (this.studentId) this.loadStudent();
            if (this.roomId) this.loadRoom();
        },
        loadStudent() {
            this.student = this.students.find(s => s.id == this.studentId) || null;
        },
        loadRoom() {
            this.room = this.rooms.find(r => r.id == this.roomId) || null;
        }
    }
}
</script>
@endpush