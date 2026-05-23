@extends('layouts.app')
@section('title', $student->full_name)

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.students.index') }}">Students</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>{{ $student->full_name }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->full_name }}</h1>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn-primary">
            <i data-feather="edit-2" class="w-4 h-4"></i> Edit
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn-secondary">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="space-y-6">
        <div class="card p-6 text-center">
            <img src="{{ $student->avatar_url }}" class="w-24 h-24 rounded-full mx-auto object-cover mb-4 ring-4 ring-primary-100 dark:ring-primary-900">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $student->full_name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-mono mt-1">{{ $student->student_id }}</p>
            <span class="{{ $student->status_badge }} mt-2">{{ ucfirst($student->status) }}</span>

            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->allocations->count() }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Allocations</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">₱{{ number_format($student->total_paid, 0) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Paid</p>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Contact Info</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <i data-feather="mail" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300 break-all">{{ $student->email }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <i data-feather="phone" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ $student->phone }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <i data-feather="map-pin" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ $student->home_address }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <i data-feather="calendar" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                    <span class="text-gray-700 dark:text-gray-300">{{ $student->date_of_birth->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Emergency Contact</h3>
            <div class="space-y-2 text-sm">
                <p class="font-medium text-gray-900 dark:text-white">{{ $student->emergency_contact_name }}</p>
                <p class="text-gray-500 dark:text-gray-400">{{ $student->emergency_contact_relation }}</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $student->emergency_contact_phone }}</p>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Academic & Current Room -->
        <div class="grid sm:grid-cols-2 gap-4">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i data-feather="book-open" class="w-4 h-4 text-primary-500"></i> Academic
                </h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Course</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $student->course }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Year Level</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">Year {{ $student->year_level }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Gender</dt>
                        <dd class="font-medium text-gray-900 dark:text-white capitalize">{{ $student->gender }}</dd>
                    </div>
                </dl>
            </div>
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i data-feather="key" class="w-4 h-4 text-primary-500"></i> Current Room
                </h3>
                @if($student->activeAllocation)
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Room</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $student->activeAllocation->room->room_number }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Dorm</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $student->activeAllocation->room->dormitory->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Check-in</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $student->activeAllocation->check_in_date->format('M d, Y') }}</dd>
                    </div>
                </dl>
                @else
                <div class="flex flex-col items-center justify-center h-16 text-gray-400 dark:text-gray-500">
                    <i data-feather="home" class="w-6 h-6 mb-1"></i>
                    <p class="text-xs">No active allocation</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Allocation History -->
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">Allocation History</h3>
                <a href="{{ route('admin.allocations.create') }}?student_id={{ $student->id }}" class="btn-primary text-xs py-1.5">
                    <i data-feather="plus" class="w-3 h-3"></i> Allocate Room
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead><tr><th>Room</th><th>Check-in</th><th>Check-out</th><th>Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($student->allocations as $alloc)
                        <tr>
                            <td>
                                <p class="font-medium">Room {{ $alloc->room->room_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $alloc->room->dormitory->name }}</p>
                            </td>
                            <td>{{ $alloc->check_in_date->format('M d, Y') }}</td>
                            <td>{{ $alloc->check_out_date?->format('M d, Y') ?? '—' }}</td>
                            <td><span class="{{ $alloc->status_badge }}">{{ ucfirst($alloc->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-6 text-center text-gray-400 dark:text-gray-500 text-sm">No allocation history</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">Payment History</h3>
                <a href="{{ route('admin.payments.create') }}?student_id={{ $student->id }}" class="btn-primary text-xs py-1.5">
                    <i data-feather="plus" class="w-3 h-3"></i> Add Payment
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead><tr><th>Reference</th><th>Type</th><th>Amount</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($student->payments as $pay)
                        <tr>
                            <td class="font-mono text-xs">{{ $pay->payment_reference }}</td>
                            <td>{{ $pay->getPaymentTypeLabel() }}</td>
                            <td class="font-semibold">₱{{ number_format($pay->amount, 2) }}</td>
                            <td>{{ $pay->payment_date->format('M d, Y') }}</td>
                            <td><span class="{{ $pay->status_badge }}">{{ ucfirst($pay->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-6 text-center text-gray-400 dark:text-gray-500 text-sm">No payment history</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection