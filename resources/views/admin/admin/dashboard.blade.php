@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <span>Home</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Welcome back, {{ auth()->user()->name }}! Here's what's happening.</p>
    </div>
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <i data-feather="clock" class="w-4 h-4"></i>
        {{ now()->format('l, F j, Y') }}
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="users" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_students']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Active Students</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="grid" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_rooms']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Rooms</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="check-square" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['available_rooms']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Available Rooms</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="key" class="w-5 h-5 text-yellow-600 dark:text-yellow-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['active_allocations']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Active Allocations</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="trending-up" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($stats['total_revenue'], 0) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Revenue This Year</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="clock" class="w-5 h-5 text-orange-600 dark:text-orange-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['pending_payments']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Pending Payments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['overdue_payments']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Overdue Payments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
            <i data-feather="home" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_dormitories']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Active Dormitories</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Monthly Revenue</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Last 6 months</p>
            </div>
        </div>
        <canvas id="revenueChart" height="120"></canvas>
    </div>

    <!-- Room Types Chart -->
    <div class="card p-6">
        <div class="mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white">Room Types</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Distribution</p>
        </div>
        <canvas id="roomChart" height="180"></canvas>
    </div>
</div>

<!-- Tables Row -->
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Allocations -->
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900 dark:text-white">Recent Allocations</h3>
            <a href="{{ route('admin.allocations.index') }}" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recentAllocations as $alloc)
            <div class="px-6 py-3 flex items-center gap-3">
                <img src="{{ $alloc->student->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $alloc->student->full_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Room {{ $alloc->room->room_number }} · {{ $alloc->room->dormitory->name }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $alloc->check_in_date->format('M d') }}</p>
                    <span class="{{ $alloc->status_badge }} text-xs">{{ ucfirst($alloc->status) }}</span>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No allocations yet</div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Checkouts -->
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900 dark:text-white">Upcoming Checkouts</h3>
            <span class="text-xs text-gray-500 dark:text-gray-400">Next 30 days</span>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($upcomingCheckouts as $alloc)
            <div class="px-6 py-3 flex items-center gap-3">
                <img src="{{ $alloc->student->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $alloc->student->full_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Room {{ $alloc->room->room_number }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @php $daysLeft = now()->diffInDays($alloc->expected_check_out_date, false); @endphp
                    <p class="text-xs font-medium {{ $daysLeft <= 7 ? 'text-red-600' : 'text-orange-500' }}">
                        {{ $daysLeft <= 0 ? 'Today' : "In {$daysLeft}d" }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $alloc->expected_check_out_date->format('M d, Y') }}</p>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No upcoming checkouts</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($recentActivities as $log)
        <div class="px-6 py-3 flex items-start gap-3">
            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-feather="{{ $log->action_icon }}" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 dark:text-gray-200">{{ $log->description }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ $log->user?->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @empty
        <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No activity yet</div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const isDark = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
const textColor = isDark ? '#9ca3af' : '#6b7280';

new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
        datasets: [{
            label: 'Revenue (₱)',
            data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
            backgroundColor: 'rgba(99, 102, 241, 0.8)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                grid: { color: gridColor },
                ticks: { color: textColor, callback: v => '₱' + v.toLocaleString() }
            },
            x: { grid: { display: false }, ticks: { color: textColor } }
        }
    }
});

// Room Types Chart
const roomCtx = document.getElementById('roomChart').getContext('2d');
const roomTypes = @json($roomTypes);
new Chart(roomCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(roomTypes).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
        datasets: [{
            data: Object.values(roomTypes),
            backgroundColor: ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { color: textColor, padding: 12, font: { size: 11 } } }
        },
        cutout: '65%',
    }
});

feather.replace();
</script>
@endpush