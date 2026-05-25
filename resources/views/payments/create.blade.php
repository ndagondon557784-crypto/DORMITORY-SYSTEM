@extends('layouts.app')
@section('title', 'Record Payment')
@section('page-title', 'Record Payment')
@section('breadcrumb', 'Payments / Create')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b" style="background:linear-gradient(135deg,#059669,#065f46)">
            <h2 class="text-lg font-bold text-white">Record Payment</h2>
        </div>

        <form action="{{ route('payments.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200">
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $err)<li>• {{ $err }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Allocation (Student / Room) *</label>
                <select name="allocation_id" class="form-input" required>
                    <option value="">Select allocation</option>
                    @foreach($activeAllocations as $alloc)
                    <option value="{{ $alloc->id }}" {{ old('allocation_id', $allocation?->id) == $alloc->id ? 'selected' : '' }}>
                        {{ $alloc->student->full_name }} — Room {{ $alloc->room->room_number }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Amount (₱) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" class="form-input" step="0.01" min="0" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" class="form-input" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Period From *</label>
                    <input type="date" name="period_from" value="{{ old('period_from') }}" class="form-input" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Period To *</label>
                    <input type="date" name="period_to" value="{{ old('period_to') }}" class="form-input" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Payment Method *</label>
                <select name="payment_method" class="form-input" required>
                    <option value="cash">Cash</option>
                    <option value="gcash">GCash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="check">Check</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Notes</label>
                <textarea name="notes" rows="2" class="form-input">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Record Payment</button>
                <a href="{{ route('payments.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection