@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div style="display:flex;flex-direction:column;gap:24px;">

    <div>
        <h1 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Dashboard</h1>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0;">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;" class="stats-grid">
        @php
        $cards = [
            ['title'=>'Active Students',    'value'=>$stats['active_students'],    'sub'=>'Currently enrolled',   'icon'=>'fa-users',          'color'=>'#004D98','bg'=>'rgba(0,77,152,.15)',   'border'=>'rgba(0,77,152,.3)'],
            ['title'=>'Available Rooms',    'value'=>$stats['available_rooms'],    'sub'=>'Ready for allocation', 'icon'=>'fa-bed',            'color'=>'#10b981','bg'=>'rgba(16,185,129,.15)', 'border'=>'rgba(16,185,129,.3)'],
            ['title'=>'Active Allocations', 'value'=>$stats['active_allocations'], 'sub'=>'Rooms assigned',       'icon'=>'fa-clipboard-list', 'color'=>'#EDBB00','bg'=>'rgba(237,187,0,.15)',  'border'=>'rgba(237,187,0,.3)'],
            ['title'=>'Pending Payments',   'value'=>$stats['pending_payments'],   'sub'=>'Requires attention',   'icon'=>'fa-credit-card',    'color'=>'#A50044','bg'=>'rgba(165,0,68,.15)',   'border'=>'rgba(165,0,68,.3)'],
        ];
        @endphp
        @foreach($cards as $c)
        <div class="card" style="padding:20px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;">
                <div>
                    <p style="font-size:11px;font-weight:500;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin:0;">{{ $c['title'] }}</p>
                    <p style="font-size:30px;font-weight:700;color:#fff;margin:6px 0 2px;">{{ $c['value'] }}</p>
                    <p style="font-size:11px;color:#64748b;margin:0;">{{ $c['sub'] }}</p>
                </div>
                <div style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};border-radius:8px;padding:10px;flex-shrink:0;">
                    <i class="fa-solid {{ $c['icon'] }}" style="color:{{ $c['color'] }};font-size:20px;"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Occupancy --}}
    <div class="card" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-chart-line" style="color:var(--barca-gold);"></i>
                <span style="font-size:14px;font-weight:600;color:#fff;">Overall Occupancy Rate</span>
            </div>
            <span style="font-size:18px;font-weight:700;color:var(--barca-gold);">{{ $occupancyRate }}%</span>
        </div>
        <div style="height:10px;background:#1e3560;border-radius:999px;overflow:hidden;">
            <div style="height:100%;width:{{ $occupancyRate }}%;background:linear-gradient(90deg,var(--barca-blue),var(--barca-maroon));border-radius:999px;transition:width .5s;"></div>
        </div>
        @if($rooms->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px;">
            @foreach($rooms as $room)
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:11px;color:#64748b;white-space:nowrap;">Rm {{ $room->room_number }}</span>
                <div style="flex:1;height:4px;background:#1e3560;border-radius:999px;overflow:hidden;">
                    @php $pct = $room->capacity > 0 ? round(($room->occupied / $room->capacity) * 100) : 0; @endphp
                    <div style="height:100%;width:{{ $pct }}%;background:{{ $room->status === 'Full' ? 'var(--barca-maroon)' : 'var(--barca-blue)' }};border-radius:999px;"></div>
                </div>
                <span style="font-size:11px;color:#64748b;">{{ $room->occupied }}/{{ $room->capacity }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Tables --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="two-col">

        {{-- Recent Allocations --}}
        <div class="card" style="overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:14px;font-weight:600;color:#fff;">Recent Allocations</span>
                <i class="fa-solid fa-clipboard-list" style="color:#64748b;"></i>
            </div>
            @forelse($recentAllocations as $a)
            <div class="table-row" style="padding:12px 20px;border-bottom:1px solid rgba(30,53,96,.5);display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:13px;font-weight:500;color:#fff;margin:0;">
                        {{ optional($a->student)->name ?? 'Unknown Student' }}
                    </p>
                    <p style="font-size:11px;color:#64748b;margin:0;">
                        Room {{ optional($a->room)->room_number ?? 'N/A' }} •
                        {{ $a->start_date ? \Carbon\Carbon::parse($a->start_date)->format('M d, Y') : 'N/A' }}
                    </p>
                </div>
                <span class="badge badge-{{ $a->status === 'Active' ? 'success' : ($a->status === 'Vacated' ? 'default' : 'warning') }}">
                    {{ $a->status }}
                </span>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:#475569;font-size:13px;">No allocations yet.</div>
            @endforelse
        </div>

        {{-- Recent Payments --}}
        <div class="card" style="overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:14px;font-weight:600;color:#fff;">Payment Status</span>
                <i class="fa-solid fa-credit-card" style="color:#64748b;"></i>
            </div>
            @forelse($recentPayments as $p)
            <div class="table-row" style="padding:12px 20px;border-bottom:1px solid rgba(30,53,96,.5);display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:13px;font-weight:500;color:#fff;margin:0;">
                        {{ optional($p->student)->name ?? 'Unknown Student' }}
                    </p>
                    <p style="font-size:11px;color:#64748b;margin:0;">
                        {{ $p->month }} {{ $p->year }} • ₱{{ number_format($p->amount, 2) }}
                    </p>
                </div>
                <span class="badge badge-{{ $p->status === 'Paid' ? 'success' : ($p->status === 'Pending' ? 'warning' : 'error') }}">
                    {{ $p->status }}
                </span>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:#475569;font-size:13px;">No payments yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Announcements --}}
    <div class="card" style="overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-bullhorn" style="color:var(--barca-gold);"></i>
                <span style="font-size:14px;font-weight:600;color:#fff;">Latest Announcements</span>
            </div>
            <a href="{{ route('announcements.index') }}" style="font-size:12px;color:#60a5fa;text-decoration:none;">View all</a>
        </div>
        @forelse($announcements as $ann)
        @php
            $dot = match($ann->type ?? 'general') {
                'urgent'      => '#ef4444',
                'maintenance' => '#f59e0b',
                default       => '#60a5fa',
            };
        @endphp
        <div class="table-row" style="padding:14px 20px;border-bottom:1px solid rgba(30,53,96,.4);display:flex;align-items:flex-start;gap:12px;">
            <div style="width:8px;height:8px;border-radius:50%;background:{{ $dot }};flex-shrink:0;margin-top:5px;"></div>
            <div style="flex:1;">
                <p style="font-size:13px;font-weight:500;color:#fff;margin:0;">{{ $ann->title }}</p>
                <p style="font-size:12px;color:#64748b;margin:2px 0 0;">
                    {{ optional($ann->author)->name ?? 'System' }} · {{ $ann->created_at->diffForHumans() }}
                </p>
            </div>
            <span class="badge badge-{{ ($ann->type ?? 'general') === 'urgent' ? 'error' : (($ann->type ?? 'general') === 'maintenance' ? 'warning' : 'info') }}" style="text-transform:capitalize;flex-shrink:0;">
                {{ $ann->type ?? 'general' }}
            </span>
        </div>
        @empty
        <div style="padding:24px;text-align:center;color:#475569;font-size:13px;">No announcements yet.</div>
        @endforelse
    </div>

    {{-- Alerts --}}
    @if($overduePayments->count() > 0 || $maintenanceRooms->count() > 0)
    <div style="background:rgba(165,0,68,.08);border:1px solid rgba(165,0,68,.2);border-radius:12px;padding:20px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <i class="fa-solid fa-triangle-exclamation" style="color:var(--barca-maroon);"></i>
            <span style="font-size:14px;font-weight:600;color:#fff;">Attention Required</span>
        </div>
        @foreach($overduePayments as $p)
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:4px;">
            <span style="color:#f87171;">•</span>
            <span style="color:#cbd5e1;">
                {{ optional($p->student)->name ?? 'Unknown' }} — overdue ₱{{ number_format($p->amount, 2) }} for {{ $p->month }} {{ $p->year }}
            </span>
        </div>
        @endforeach
        @foreach($maintenanceRooms as $r)
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:4px;">
            <span style="color:#fbbf24;">•</span>
            <span style="color:#cbd5e1;">Room {{ $r->room_number }} is under maintenance</span>
        </div>
        @endforeach
    </div>
    @endif

</div>

<style>
@media(min-width:768px) {
    .stats-grid { grid-template-columns: repeat(4,1fr) !important; }
    .two-col    { grid-template-columns: repeat(2,1fr) !important; }
}
@media(max-width:767px) {
    .two-col { grid-template-columns: 1fr !important; }
}
</style>
@endsection