<x-app-layout>
    <div class="space-y-6">
        <!-- Allocation Card -->
        @if($currentAllocation)
            <x-card class="bg-gradient-to-br from-royal/5 to-royal/10 border-l-4 border-royal">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-navy">Your Room</h3>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        Active
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Room Number</p>
                        <p class="text-2xl font-bold text-navy">{{ $currentAllocation->room->room_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Building</p>
                        <p class="text-2xl font-bold text-navy">{{ $currentAllocation->room->building->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Monthly Rent</p>
                        <p class="text-2xl font-bold text-gold">₱{{ number_format($currentAllocation->room->monthly_rent, 2) }}</p>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Check-in Date</p>
                        <p class="font-semibold text-navy">{{ $currentAllocation->check_in_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Days Occupied</p>
                        <p class="text-2xl font-bold text-royal">{{ $currentAllocation->days_occupied }}</p>
                    </div>
                </div>
            </x-card>
        @else
            <x-card class="bg-amber-50 border-l-4 border-amber-500">
                <p class="text-amber-900">You are not currently allocated to any room. Please contact the administration.</p>
            </x-card>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Paid</p>
                        <p class="text-3xl font-bold text-green-600">₱{{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Outstanding Balance</p>
                        <p class="text-3xl font-bold text-red-600">₱{{ number_format($outstandingBalance, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Payments Made</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $recentPayments->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Recent Payments -->
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-navy">Recent Payments</h3>
                <a href="#" class="text-sm font-medium text-royal hover:text-maroon transition-smooth">
                    View All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Amount</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Method</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-smooth">
                                <td class="py-3 px-4 font-semibold text-navy">₱{{ number_format($payment->amount, 2) }}</td>
                                <td class="py-3 px-4 text-slate-600">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="py-3 px-4 text-slate-600 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                <td class="py-3 px-4">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-xs font-semibold',
                                        'bg-green-100 text-green-700' => $payment->status === 'completed',
                                        'bg-yellow-100 text-yellow-700' => $payment->status === 'pending',
                                    ])>
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 px-4 text-center text-slate-600">
                                    No payments recorded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>