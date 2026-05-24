<x-app-layout>
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-card class="bg-gradient-to-br from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Students</p>
                        <p class="text-3xl font-bold text-navy">{{ $totalStudents }}</p>
                    </div>
                    <div class="w-12 h-12 bg-royal/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-royal" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-gradient-to-br from-amber-50 to-orange-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Rooms</p>
                        <p class="text-3xl font-bold text-navy">{{ $totalRooms }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gold/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-maroon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H3a1.5 1.5 0 00-1.5 1.5v12a1.5 1.5 0 001.5 1.5h13a1.5 1.5 0 001.5-1.5V6.621a1.5 1.5 0 00-.44-1.06l-3.12-3.121A1.5 1.5 0 0013.38 1.5h-2.88z"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-gradient-to-br from-emerald-50 to-teal-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Occupied Rooms</p>
                        <p class="text-3xl font-bold text-navy">{{ $occupiedRooms }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card class="bg-gradient-to-br from-purple-50 to-pink-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Revenue</p>
                        <p class="text-3xl font-bold text-navy">₱{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gold/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Payments -->
            <div class="lg:col-span-2">
                <x-card>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-navy">Recent Payments</h3>
                        <a href="{{ route('payments.index') }}" class="text-sm font-medium text-royal hover:text-maroon transition-smooth">
                            View All
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="text-left py-3 px-4 font-semibold text-slate-700">Student</th>
                                    <th class="text-left py-3 px-4 font-semibold text-slate-700">Amount</th>
                                    <th class="text-left py-3 px-4 font-semibold text-slate-700">Date</th>
                                    <th class="text-left py-3 px-4 font-semibold text-slate-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-smooth">
                                        <td class="py-3 px-4">{{ $payment->student->user->name }}</td>
                                        <td class="py-3 px-4 font-semibold text-navy">₱{{ number_format($payment->amount, 2) }}</td>
                                        <td class="py-3 px-4 text-slate-600">{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td class="py-3 px-4">
                                            <span @class([
                                                'px-3 py-1 rounded-full text-xs font-semibold',
                                                'bg-green-100 text-green-700' => $payment->status === 'completed',
                                                'bg-yellow-100 text-yellow-700' => $payment->status === 'pending',
                                                'bg-red-100 text-red-700' => $payment->status === 'failed',
                                            ])>
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>

            <!-- Announcements -->
            <div>
                <x-card>
                    <h3 class="text-lg font-bold text-navy mb-6">Announcements</h3>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @forelse($announcements as $announcement)
                            <div class="p-4 glass-effect rounded-lg">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-navy text-sm">{{ $announcement->title }}</h4>
                                    @if($announcement->is_pinned)
                                        <svg class="w-4 h-4 text-gold" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z"/>
                                        </svg>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2">{{ $announcement->content }}</p>
                                <div class="flex items-center justify-between mt-3">
                                    <span @class([
                                        'text-xs font-semibold px-2 py-1 rounded-full',
                                        'bg-red-100 text-red-700' => $announcement->type === 'urgent',
                                        'bg-blue-100 text-blue-700' => $announcement->type === 'general',
                                        'bg-yellow-100 text-yellow-700' => $announcement->type === 'maintenance',
                                        'bg-purple-100 text-purple-700' => $announcement->type === 'event',
                                    ])>
                                        {{ ucfirst($announcement->type) }}
                                    </span>
                                    <span class="text-xs text-slate-500">{{ $announcement->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-600 text-center py-8">No announcements yet</p>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>