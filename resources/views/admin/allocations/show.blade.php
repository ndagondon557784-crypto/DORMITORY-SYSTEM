@extends('layouts.app')
@section('title', 'Allocation #' . $allocation->id)

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.allocations.index') }}">Allocations</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>#{{ $allocation->id }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Allocation #{{ $allocation->id }}</h1>
    </div>
    <div class="flex gap-2">
        @if($allocation->status === 'active')
        <form action="{{ route('admin.allocations.checkout', $allocation) }}" method="POST"
              onsubmit="return confirm('Check out {{ $allocation->student->full_name }}?')">
            @csrf
            <button class="btn-secondary text-orange-600 border-orange-200 hover:bg-orange-50">
                <i data-feather="log-out" class="w-4 h-4"></i> Check Out
            </button>
        </form>
        @endif
        <a href="{{ route('admin.allocations.edit', $allocation) }}" class="btn-primary">
            <i data-feather="edit-2" class="w-4 h-4"></i> Edit
        </a>
        <a href="{{ route('admin.allocations.index') }}" class="btn-secondary">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="card p-6 text-center">
            <span class="{{ $allocation->status_badge }} text-sm capitalize mb-3 inline-block">{{ str_replace('_',' ',$allocation->status) }}</span>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $allocation->duration_in_days }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Days {{ $allocation->status === 'active' ? 'Ongoing' : 'Total' }}</p>
        </div>

        <!-- Student Card -->
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Student</h3>
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $allocation->student->avatar_url }}" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $allocation->student->full_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $allocation->student->student_id }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $allocation->student->course }} · Year {{ $allocation->student->year_level }}</p>
            <a href="{{ route('admin.students.show', $allocation->student) }}" class="text-xs text-primary-600 hover:underline mt-2 inline-block">View full profile →</a>
        </div>

        <!-- Room Card -->
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Room</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">Room {{ $allocation->room->room_number }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $allocation->room->dormitory->name }}</p>
            <dl class="mt-3 space-y-1 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Floor</dt>
                    <dd class="font-medium">{{ $allocation->room->floor_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Type</dt>
                    <dd class="font-medium capitalize">{{ $allocation->room->room_type }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Rate/mo</dt>
                    <dd class="font-bold text-primary-600 dark:text-primary-400">₱{{ number_format($allocation->room->monthly_rate, 2) }}</dd>
                </div>
            </dl>
            <a href="{{ route('admin.rooms.show', $allocation->room) }}" class="text-xs text-primary-600 hover:underline mt-2 inline-block">View room →</a>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <!-- Allocation Details -->
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Allocation Details</h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Check-in Date</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $allocation->check_in_date->format('F d, Y') }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Expected Check-out</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $allocation->expected_check_out_date?->format('F d, Y') ?? 'Not set' }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Actual Check-out</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $allocation->check_out_date?->format('F d, Y') ?? '—' }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Security Deposit</p>
                    <p class="font-semibold text-gray-900 dark:text-white">₱{{ number_format($allocation->deposit_amount, 2) }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Allocated By</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $allocation->allocatedBy->name }}</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Paid</p>
                    <p class="font-bold text-green-600">₱{{ number_format($allocation->total_paid, 2) }}</p>
                </div>
            </div>
            @if($allocation->notes)
            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                <p class="text-xs text-yellow-700 dark:text-yellow-400 font-medium mb-1">Notes</p>
                <p class="text-sm text-yellow-800 dark:text-yellow-300">{{ $allocation->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">Payment History</h3>
                <a href="{{ route('admin.payments.create') }}?allocation_id={{ $allocation->id }}" class="btn-primary text-xs py-1.5">
                    <i data-feather="plus" class="w-3 h-3"></i> Add Payment
                </a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead><tr><th>Reference</th><th>Type</th><th>Method</th><th>Amount</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($allocation->payments as $pay)
                        <tr>
                            <td class="font-mono text-xs">{{ $pay->payment_reference }}</td>
                            <td>{{ $pay->getPaymentTypeLabel() }}</td>
                            <td class="capitalize">{{ str_replace('_',' ',$pay->payment_method) }}</td>
                            <td class="font-semibold">₱{{ number_format($pay->amount, 2) }}</td>
                            <td>{{ $pay->payment_date->format('M d, Y') }}</td>
                            <td><span class="{{ $pay->status_badge }}">{{ ucfirst($pay->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-6 text-center text-sm text-gray-400 dark:text-gray-500">No payments recorded</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection