@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php $statCards = [
        ['label'=>'Active Students','value'=>$stats['total_students'],'icon'=>'users','color'=>'indigo','sub'=>'enrolled'],
        ['label'=>'Available Rooms','value'=>$stats['available_rooms'],'icon'=>'building','color'=>'green','sub'=>'of '.$stats['total_rooms'].' total'],
        ['label'=>'Active Allocations','value'=>$stats['active_allocations'],'icon'=>'calendar-check','color'=>'blue','sub'=>$stats['pending_allocations'].' pending'],
        ['label'=>'Monthly Revenue','value'=>'₱'.number_format($stats['monthly_revenue'],0),'icon'=>'trending-up','color'=>'amber','sub'=>'this month'],
    ] @endphp
    @foreach($statCards as $card)
    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 bg-{{ $card['color'] }}-100 rounded-2xl flex items-center justify-center">
                <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5 text-{{ $card['color'] }}-600"></i>
            </div>
        </div>
        <div class="font-display text-2xl font-700 text-slate-900 mb-1">{{ $card['value'] }}</div>
        <div class="text-sm font-600 text-slate-700 mb-0.5">{{ $card['label'] }}</div>
        <div class="text-xs text-slate-400">{{ $card['sub'] }}</div>
    </div>
    @endforeach
</div>

<!-- Occupancy Rate Banner -->
<div class="card mb-6 p-6 bg-gradient-to-r from-indigo-600 to-purple-600 border-0">
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <div class="text-indigo-200 text-sm font-600 mb-1">Overall Occupancy Rate</div>
            <div class="font-display text-5xl font-800 text-white">{{ $occupancyRate }}%</div>
            <div class="text-indigo-200 text-sm mt-1">{{ $stats['occupied_rooms'] }} of {{ $stats['total_rooms'] }} rooms occupied</div>
        </div>
        <div class="w-full md:w-64">
            <div class="flex justify-between text-indigo-200 text-xs mb-2">
                <span>Occupancy</span><span>{{ $occupancyRate }}%</span>
            </div>
            <div class="h-3 bg-indigo-800 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $occupancyRate }}%"></div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                <div><div class="text-white font-700">{{ $stats['occupied_rooms'] }}</div><div class="text-indigo-200 text-xs">Occupied</div></div>
                <div><div class="text-white font-700">{{ $stats['available_rooms'] }}</div><div class="text-indigo-200 text-xs">Available</div></div>
                <div><div class="text-white font-700">{{ $stats['total_rooms'] - $stats['occupied_rooms'] - $stats['available_rooms'] }}</div><div class="text-indigo-200 text-xs">Other</div></div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <div>
                <h3 class="font-display font-700 text-slate-900">Revenue Overview</h3>
                <p class="text-slate-400 text-sm">Monthly revenue for {{ date('Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-slate-500">Total</div>
                <div class="font-700 text-slate-900">₱{{ number_format($stats['total_revenue'], 0) }}</div>
            </div>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" height="200"></canvas>
        </div>
    </div>

    <!-- Room Occupancy -->
    <div class="card">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-display font-700 text-slate-900">Room Occupancy</h3>
            <p class="text-slate-400 text-sm">Top occupied rooms</p>
        </div>
        <div class="p-6 space-y-4">
            @foreach($roomOccupancy as $room)
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-600 text-slate-700">Room {{ $room->room_number }}</span>
                    <span class="text-xs text-slate-500">{{ $room->active_allocations_count }}/{{ $room->capacity }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill bg-indigo-500" style="width: {{ $room->capacity > 0 ? ($room->active_allocations_count/$room->capacity)*100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
            @if($roomOccupancy->isEmpty())
            <div class="text-center text-slate-400 py-4 text-sm">No room data available</div>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Allocations -->
    <div class="card">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <div>
                <h3 class="font-display font-700 text-slate-900">Recent Allocations</h3>
                <p class="text-slate-400 text-sm">Latest room assignments</p>
            </div>
            <a href="{{ route('allocations.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-600">View all</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentAllocations as $allocation)
            <div class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user" class="w-4 h-4 text-indigo-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-600 text-slate-900 truncate">{{ $allocation->student->full_name }}</div>
                    <div class="text-xs text-slate-500">Room {{ $allocation->room->room_number }} • {{ $allocation->start_date->format('M d, Y') }}</div>
                </div>
                <span class="badge badge-{{ $allocation->status === 'active' ? 'success' : ($allocation->status === 'pending' ? 'warning' : 'gray') }}">
                    {{ ucfirst($allocation->status) }}
                </span>
            </div>
            @empty
            <div class="text-center text-slate-400 py-8 text-sm">No allocations yet</div>
            @endforelse
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="card">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <div>
                <h3 class="font-display font-700 text-slate-900">Recent Payments</h3>
                <p class="text-slate-400 text-sm">Latest payment records</p>
            </div>
            <a href="{{ route('payments.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-600">View all</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentPayments as $payment)
            <div class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="credit-card" class="w-4 h-4 text-green-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-600 text-slate-900 truncate">{{ $payment->student->full_name }}</div>
                    <div class="text-xs text-slate-500">{{ $payment->reference_number }} • {{ $payment->payment_date->format('M d') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-700 text-slate-900">₱{{ number_format($payment->total_amount, 0) }}</div>
                    <span class="badge badge-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }} text-xs">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center text-slate-400 py-8 text-sm">No payments yet</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Activity Log -->
<div class="card">
    <div class="flex items-center justify-between p-6 border-b border-slate-100">
        <div>
            <h3 class="font-display font-700 text-slate-900">Activity Log</h3>
            <p class="text-slate-400 text-sm">Recent system activities</p>
        </div>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @forelse($activityLogs as $log)
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="activity" class="w-3.5 h-3.5 text-slate-600"></i>
                </div>
                <div class="flex-1">
                    <div class="text-sm text-slate-700">{{ $log->description }}</div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-slate-400">{{ $log->user ? $log->user->name : 'System' }}</span>
                        <span class="text-slate-300">•</span>
                        <span class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <span class="badge badge-gray text-xs">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
            </div>
            @empty
            <div class="text-center text-slate-400 py-4 text-sm">No activity yet</div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const data = @json($chartData);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Revenue (₱)',
                data,
                backgroundColor: 'rgba(79,70,229,0.15)',
                borderColor: '#4f46e5',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: {
                callbacks: { label: ctx => '₱' + ctx.raw.toLocaleString() }
            }},
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => '₱'+v.toLocaleString() } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endsection