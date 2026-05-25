@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Home / Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <div class="stat-card" style="background:linear-gradient(135deg,#004d98,#0a1628)">
        <div class="flex items-center justify-between mb-3">
            <div class="text-white/70 text-sm font-medium">Total Students</div>
            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white">{{ number_format($stats['total_students']) }}</div>
        <div class="text-white/50 text-xs mt-1">Active enrolled students</div>
    </div>

    <div class="stat-card" style="background:linear-gradient(135deg,#a50044,#6b0029)">
        <div class="flex items-center justify-between mb-3">
            <div class="text-white/70 text-sm font-medium">Active Allocations</div>
            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white">{{ number_format($stats['active_allocations']) }}</div>
        <div class="text-white/50 text-xs mt-1">Currently allocated rooms</div>
    </div>

    <div class="stat-card" style="background:linear-gradient(135deg,#059669,#065f46)">
        <div class="flex items-center justify-between mb-3">
            <div class="text-white/70 text-sm font-medium">Available Rooms</div>
            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white">{{ number_format($stats['available_rooms']) }}</div>
        <div class="text-white/50 text-xs mt-1">Rooms open for booking</div>
    </div>

    <div class="stat-card" style="background:linear-gradient(135deg,#d97706,#92400e)">
        <div class="flex items-center justify-between mb-3">
            <div class="text-white/70 text-sm font-medium">Total Revenue</div>
            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-white">₱{{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="text-white/50 text-xs mt-1">Collected payments</div>
    </div>
</div>

{{-- Occupancy + Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Occupancy Rate --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-4">Overall Occupancy</h3>
        <div class="flex items-center justify-center">
            <div class="relative w-36 h-36">
                <canvas id="occupancyChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-extrabold text-gray-800 dark:text-white">{{ $stats['occupancy_rate'] }}%</span>
                    <span class="text-xs text-gray-400">Occupied</span>
                </div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs">
            <div><div class="font-bold text-gray-800 dark:text-white">{{ $stats['total_rooms'] }}</div><div class="text-gray-400">Total</div></div>
            <div><div class="font-bold text-blue-600">{{ $stats['total_rooms'] - $stats['available_rooms'] }}</div><div class="text-gray-400">Occupied</div></div>
            <div><div class="font-bold text-emerald-600">{{ $stats['available_rooms'] }}</div><div class="text-gray-400">Free</div></div>
        </div>
    </div>

    {{-- Monthly Revenue Chart --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-4">Monthly Revenue (Last 6 Months)</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

{{-- Recent Tables --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Recent Allocations --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-white text-sm">Recent Allocations</h3>
            <a href="{{ route('allocations.index') }}" class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-gray-700">
            @forelse($recentAllocations as $alloc)
            <div class="flex items-center gap-4 px-6 py-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background:linear-gradient(135deg,#004d98,#a50044)">
                    {{ strtoupper(substr($alloc->student->full_name,0,2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $alloc->student->full_name }}</div>
                    <div class="text-xs text-gray-400">Room {{ $alloc->room->room_number }} — {{ $alloc->room->building->name }}</div>
                </div>
                <span class="badge {{ $alloc->status === 'active' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($alloc->status) }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No allocations yet</div>
            @endforelse
        </div>
    </div>

    {{-- Announcements --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-white text-sm">Latest Announcements</h3>
            <a href="{{ route('announcements.index') }}" class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-gray-700">
            @forelse($announcements as $ann)
            <div class="px-6 py-3">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0 {{ match($ann->type) { 'urgent'=>'bg-red-500', 'maintenance'=>'bg-yellow-500', 'event'=>'bg-blue-500', default=>'bg-emerald-500' } }}"></div>
                    <div>
                        <div class="text-sm font-medium text-gray-800 dark:text-white">{{ $ann->title }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $ann->author->name }} · {{ $ann->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No announcements yet</div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
// Occupancy Donut
const occ = {{ $stats['occupancy_rate'] }};
new Chart(document.getElementById('occupancyChart'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [occ, 100-occ],
            backgroundColor: ['#004d98', '#f3f4f6'],
            borderWidth: 0,
        }]
    },
    options: { cutout: '78%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }
});

// Revenue Bar
const months = @json(array_column($monthlyRevenue, 'month'));
const amounts = @json(array_column($monthlyRevenue, 'revenue'));
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (₱)',
            data: amounts,
            backgroundColor: 'rgba(0,77,152,0.8)',
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: v => '₱'+v.toLocaleString() } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
@endsection