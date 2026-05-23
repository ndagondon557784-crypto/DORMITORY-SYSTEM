@extends('layouts.app')
@section('title', 'Payment ' . $payment->payment_reference)

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('admin.payments.index') }}">Payments</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>{{ $payment->payment_reference }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $payment->payment_reference }}</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.payments.edit', $payment) }}" class="btn-primary">
            <i data-feather="edit-2" class="w-4 h-4"></i> Edit
        </a>
        <a href="{{ route('admin.payments.index') }}" class="btn-secondary">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Receipt Card -->
    <div class="space-y-6">
        <div class="card p-6 text-center">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-feather="credit-card" class="w-7 h-7 text-green-600 dark:text-green-400"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($payment->amount, 2) }}</p>
            <span class="{{ $payment->status_badge }} mt-2 inline-block">{{ ucfirst($payment->status) }}</span>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-3 font-mono">{{ $payment->payment_reference }}</p>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Student</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $payment->student->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-medium text-sm">{{ $payment->student->full_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $payment->student->student_id }}</p>
                </div>
            </div>
            <a href="{{ route('admin.students.show', $payment->student) }}" class="text-xs text-primary-600 hover:underline mt-2 inline-block">View profile →</a>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-5">Payment Details</h3>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach([
                    ['Type', $payment->getPaymentTypeLabel()],
                    ['Method', ucwords(str_replace('_',' ',$payment->payment_method))],
                    ['Payment Date', $payment->payment_date->format('F d, Y')],
                    ['Due Date', $payment->due_date?->format('F d, Y') ?? '—'],
                    ['Period', $payment->period_month ?? '—'],
                    ['Receipt No.', $payment->receipt_number ?? '—'],
                    ['Received By', $payment->receivedBy->name],
                    ['Room', 'Room '.$payment->allocation->room->room_number.' · '.$payment->allocation->room->dormitory->name],
                ] as [$label, $value])
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $label }}</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $value }}</p>
                </div>
                @endforeach
            </div>
            @if($payment->notes)
            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                <p class="text-xs text-yellow-700 dark:text-yellow-400 font-medium mb-1">Notes</p>
                <p class="text-sm text-yellow-800 dark:text-yellow-300">{{ $payment->notes }}</p>
            </div>
            @endif
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500">
                <span>Created: {{ $payment->created_at->format('M d, Y H:i') }}</span>
                <span>Updated: {{ $payment->updated_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection