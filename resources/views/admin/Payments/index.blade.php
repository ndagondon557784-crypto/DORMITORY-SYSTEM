@extends('layouts.app')
@section('title', 'Payments')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span>Payments</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payments</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track all rent and fee transactions</p>
    </div>
    <a href="{{ route('admin.payments.create') }}" class="btn-primary">
        <i data-feather="plus" class="w-4 h-4"></i> Record Payment
    </a>
</div>

<!-- Summary cards -->
<div class="grid sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
        </div>
        <div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">₱{{ number_format($totalPaid, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Collected</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="clock" class="w-5 h-5 text-yellow-600 dark:text-yellow-400"></i>
        </div>
        <div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">₱{{ number_format($totalPending, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
        </div>
        <div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">₱{{ number_format($totalOverdue, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Overdue</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reference or student..." class="form-input pl-9">
        </div>
        <select name="status" class="form-input w-full sm:w-36">
            <option value="">All Status</option>
            @foreach(['paid','pending','overdue','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="payment_type" class="form-input w-full sm:w-44">
            <option value="">All Types</option>
            @foreach(['monthly_rent' => 'Monthly Rent','deposit' => 'Deposit','utility' => 'Utility','penalty' => 'Penalty','other' => 'Other'] as $v => $l)
            <option value="{{ $v }}" {{ request('payment_type') == $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <input type="month" name="month" value="{{ request('month') }}" class="form-input w-full sm:w-40">
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','status','payment_type','month']))
        <a href="{{ route('admin.payments.index') }}" class="btn-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Student</th>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($payments as $pay)
                <tr>
                    <td class="font-mono text-xs text-gray-600 dark:text-gray-400">{{ $pay->payment_reference }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <img src="{{ $pay->student->avatar_url }}" class="w-7 h-7 rounded-full object-cover flex-shrink-0">
                            <span class="font-medium text-sm truncate max-w-[120px]">{{ $pay->student->full_name }}</span>
                        </div>
                    </td>
                    <td class="text-sm">
                        @if($pay->allocation->room)
                        Room {{ $pay->allocation->room->room_number }}
                        @else —
                        @endif
                    </td>
                    <td class="text-sm">{{ $pay->getPaymentTypeLabel() }}</td>
                    <td class="text-sm capitalize">{{ str_replace('_',' ',$pay->payment_method) }}</td>
                    <td class="font-bold text-gray-900 dark:text-white">₱{{ number_format($pay->amount, 2) }}</td>
                    <td class="text-sm">{{ $pay->payment_date->format('M d, Y') }}</td>
                    <td><span class="{{ $pay->status_badge }}">{{ ucfirst($pay->status) }}</span></td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.payments.show', $pay) }}" class="p-1.5 rounded-lg text-gray-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.payments.edit', $pay) }}" class="p-1.5 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.payments.destroy', $pay) }}" method="POST"
                                  onsubmit="return confirm('Delete payment {{ $pay->payment_reference }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <i data-feather="credit-card" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No payments found</p>
                        <a href="{{ route('admin.payments.create') }}" class="btn-primary mt-4 inline-flex">
                            <i data-feather="plus" class="w-4 h-4"></i> Record Payment
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $payments->links() }}</div>
    @endif
</div>
@endsection