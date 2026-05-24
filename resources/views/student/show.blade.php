@extends('layouts.app')
@section('title', $student->full_name)
@section('content')

<div class="page-header">
    <div>
        <a href="{{ route('students.index') }}" class="text-slate-400 hover:text-slate-600 flex items-center gap-1 text-sm mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Students
        </a>
        <h2 class="font-display text-2xl font-700 text-slate-900">{{ $student->full_name }}</h2>
    </div>
    <div class="flex gap-3">
        @if(!$student->activeAllocation)
        <form method="POST" action="{{ route('students.auto-allocate', $student) }}" onsubmit="return confirm('Auto-allocate this student?')">
            @csrf
            <button type="submit" class="btn btn-success">
                <i data-lucide="zap" class="w-4 h-4"></i> Auto Allocate
            </button>
        </form>
        @endif
        <a href="{{ route('students.edit', $student) }}" class="btn btn-secondary">
            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
        </a>
        <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this student? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="space-y-6">
        <div class="card p-6 text-center">
            <img src="{{ $student->photo_url }}" alt="{{ $student->full_name }}" class="w-28 h-28 rounded-2xl object-cover mx-auto mb-4 border-4 border-slate-100">
            <h3 class="font-display font-700 text-xl text-slate-900">{{ $student->full_name }}</h3>
            <div class="text-indigo-600 font-600 text-sm mb-3">{{ $student->student_id }}</div>
            <span class="badge badge-{{ $student->status === 'active' ? 'success' : ($student->status === 'suspended' ? 'danger' : 'gray') }} text-sm">
                {{ ucfirst($student->status) }}
            </span>

            <div class="mt-6 space-y-3 text-left">
                <div class="flex items-center gap-3 text-sm">
                    <i data-lucide="mail" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                    <span class="text-slate-600 truncate">{{ $student->email }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <i data-lucide="phone" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                    <span class="text-slate-600">{{ $student->phone }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <i data-lucide="user" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                    <span class="text-slate-600">{{ ucfirst($student->gender) }}</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                    <span class="text-slate-600">{{ $student->date_of_birth->format('M d, Y') }}</span>
                </div>
                <div class="flex items-start gap-3 text-sm">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5"></i>
                    <span class="text-slate-600">{{ $student->address }}</span>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h4 class="font-display font-700 text-slate-900 mb-4">Academic Info</h4>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-slate-400 font-600 uppercase">Course</dt>
                    <dd class="text-sm font-600 text-slate-700 mt-0.5">{{ $student->course }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400 font-600 uppercase">Year Level</dt>
                    <dd class="text-sm font-600 text-slate-700 mt-0.5">Year {{ $student->year_level }}</dd>
                </div>
            </dl>
        </div>

        <div class="card p-6">
            <h4 class="font-display font-700 text-slate-900 mb-4">Guardian</h4>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-slate-400 font-600 uppercase">Name</dt>
                    <dd class="text-sm font-600 text-slate-700 mt-0.5">{{ $student->guardian_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400 font-600 uppercase">Phone</dt>
                    <dd class="text-sm font-600 text-slate-700 mt-0.5">{{ $student->guardian_phone }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400 font-600 uppercase">Relationship</dt>
                    <dd class="text-sm font-600 text-slate-700 mt-0.5">{{ $student->guardian_relationship }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Current Allocation -->
        <div class="card p-6">
            <h4 class="font-display font-700 text-slate-900 mb-4">Current Room Allocation</h4>
            @if($student->activeAllocation)
            <div class="flex items-center gap-4 p-4 bg-green-50 border border-green-100 rounded-2xl">
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="building" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="flex-1">
                    <div class="font-700 text-slate-900">Room {{ $student->activeAllocation->room->room_number }}</div>
                    <div class="text-sm text-slate-500">{{ $student->activeAllocation->room->building }} • {{ $student->activeAllocation->room->type }}</div>
                    <div class="text-xs text-slate-400 mt-1">{{ $student->activeAllocation->start_date->format('M d, Y') }} — {{ $student->activeAllocation->end_date->format('M d, Y') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-indigo-600 font-700">₱{{ number_format($student->activeAllocation->room->monthly_rate, 0) }}/mo</div>
                    <a href="{{ route('allocations.show', $student->activeAllocation) }}" class="btn btn-sm btn-secondary mt-2">View</a>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-slate-400">
                <i data-lucide="building" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                <p class="text-sm">No active room allocation</p>
                <div class="flex gap-2 justify-center mt-3">
                    <a href="{{ route('allocations.create') }}?student_id={{ $student->id }}" class="btn btn-sm btn-primary">Assign Room</a>
                    <form method="POST" action="{{ route('students.auto-allocate', $student) }}">
                        @csrf <button class="btn btn-sm btn-success"><i data-lucide="zap" class="w-3 h-3"></i> Auto</button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Allocation History -->
        <div class="card">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h4 class="font-display font-700 text-slate-900">Allocation History</h4>
                <span class="badge badge-gray">{{ $student->allocations->count() }} total</span>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($student->allocations as $alloc)
                <div class="flex items-center gap-4 p-4">
                    <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="calendar" class="w-4 h-4 text-indigo-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-600 text-slate-900">Room {{ $alloc->room->room_number }} — {{ $alloc->room->building }}</div>
                        <div class="text-xs text-slate-500">{{ $alloc->start_date->format('M d, Y') }} → {{ $alloc->end_date->format('M d, Y') }}</div>
                    </div>
                    <span class="badge badge-{{ $alloc->status === 'active' ? 'success' : ($alloc->status === 'pending' ? 'warning' : 'gray') }}">
                        {{ ucfirst($alloc->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center text-slate-400 py-8 text-sm">No allocation history</div>
                @endforelse
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h4 class="font-display font-700 text-slate-900">Payment History</h4>
                <a href="{{ route('payments.create') }}?allocation_id={{ optional($student->activeAllocation)->id }}" class="btn btn-sm btn-primary">
                    <i data-lucide="plus" class="w-3 h-3"></i> Add Payment
                </a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($student->payments as $payment)
                <div class="flex items-center gap-4 p-4">
                    <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-4 h-4 text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-600 text-slate-900">{{ $payment->reference_number }}</div>
                        <div class="text-xs text-slate-500">{{ $payment->payment_date->format('M d, Y') }} • {{ ucfirst($payment->payment_method) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-700 text-slate-900">₱{{ number_format($payment->total_amount, 2) }}</div>
                        <span class="badge badge-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-400 py-8 text-sm">No payment records</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection