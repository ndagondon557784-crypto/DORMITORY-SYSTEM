@extends('layouts.app')
@section('title','Dashboard')
@section('content')

<div style="display:flex;flex-direction:column;gap:24px;">
    <div>
        <h1 style="font-size:20px;font-weight:700;color:#fff;">Dashboard</h1>
        <p style="font-size:13px;color:#64748b;margin-top:2px;">Welcome back, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stat cards -->
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;" class="stats-grid">
        @php
        $cards = [
            ['title'=>'Active Students','value'=>$stats['active_students'],'sub'=>'Currently enrolled','icon'=>'fa-users','color'=>'#004D98','bg'=>'rgba(0,77,152,.15)','border'=>'rgba(0,77,152,.3)'],
            ['title'=>'Available Rooms','value'=>$stats['available_rooms'],'sub'=>'Ready for allocation','icon'=>'fa-bed','color'=>'#10b981','bg'=>'rgba(16,185,129,.15)','border'=>'rgba(16,185,129,.3)'],
            ['title'=>'Active Allocations','value'=>$stats['active_allocations'],'sub'=>'Rooms assigned','icon'=>'fa-clipboard-list','color'=>'#EDBB00','bg'=>'rgba(237,187,0,.15)','border'=>'rgba(237,187,0,.3)'],
            ['title'=>'Pending Payments','value'=>$stats['pending_payments'],'sub'=>'Requires attention','icon'=>'fa-credit-card','color'=>'#A50044','bg'=>'rgba(165,0,68,.15)','border'=>'rgba(165,0,68,.3)'],
        ];
        @endphp
        @foreach($cards as $c)
        <div class="card" style="padding:20px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;">
                <div>
                    <p style="font-size:11px;font-weight:500;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">{{ $c['title'] }}</p>
                    <p style="font-size:30px;font-weight:700;color:#fff;margin-top:6px;">{{ $c['value'] }}</p>
                    <p style="font-size:11px;color:#64748b;margin-top:2px;">{{ $c['sub'] }}</p>
                </div>
                <div style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};border-radius:8px;padding:10px;">
                    <i class="fa-solid {{ $c['icon'] }}" style="color:{{ $c['color'] }};font-size:20px;"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Occupancy bar -->
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
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px;">
            @foreach($rooms as $room)
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:11px;color:#64748b;white-space:nowrap;">Rm {{ $room->room_number }}</span>
                <div style="flex:1;height:4px;background:#1e3560;border-radius:999px;overflow:hidden;">
                    <div style="height:100%;width:{{ $room->capacity > 0 ? round(($room->occupied/$room->capacity)*100) : 0 }}%;background:{{ $room->status==='Full' ? 'var(--barca-maroon)' : 'var(--barca-blue)' }};border-radius:999px;"></div>
                </div>
                <span style="font-size:11px;color:#64748b;">{{ $room->occupied }}/{{ $room->capacity }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tables -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="two-col">
        <!-- Recent Allocations -->
        <div class="card" style="overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:14px;font-weight:600;color:#fff;">Recent Allocations</span>
                <i class="fa-solid fa-clipboard-list" style="color:#64748b;"></i>
            </div>
            @foreach($recentAllocations as $a)
            <div class="table-row" style="padding:12px 20px;border-bottom:1px solid rgba(30,53,96,.5);display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:13px;font-weight:500;color:#fff;">{{ $a->student->name }}</p>
                    <p style="font-size:11px;color:#64748b;">Room {{ $a->room->room_number }} • {{ \Carbon\Carbon::parse($a->start_date)->format('M d, Y') }}</p>
                </div>
                <span class="badge badge-{{ $a->status==='Active' ? 'success' : ($a->status==='Vacated' ? 'default' : 'warning') }}">{{ $a->status }}</span>
            </div>
            @endforeach
        </div>

        <!-- Payments -->
        <div class="card" style="overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:14px;font-weight:600;color:#fff;">Payment Status</span>
                <i class="fa-solid fa-credit-card" style="color:#64748b;"></i>
            </div>
            @foreach($recentPayments as $p)
            <div class="table-row" style="padding:12px 20px;border-bottom:1px solid rgba(30,53,96,.5);display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:13px;font-weight:500;color:#fff;">{{ $p->student->name }}</p>
                    <p style="font-size:11px;color:#64748b;">{{ $p->month }} {{ $p->year }} • ₱{{ number_format($p->amount,2) }}</p>
                </div>
                <span class="badge badge-{{ $p->status==='Paid' ? 'success' : ($p->status==='Pending' ? 'warning' : 'error') }}">{{ $p->status }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Alerts -->
    @if($overduePayments->count() > 0 || $maintenanceRooms->count() > 0)
    <div style="background:rgba(165,0,68,.08);border:1px solid rgba(165,0,68,.2);border-radius:12px;padding:20px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <i class="fa-solid fa-triangle-exclamation" style="color:var(--barca-maroon);"></i>
            <span style="font-size:14px;font-weight:600;color:#fff;">Attention Required</span>
        </div>
        @foreach($overduePayments as $p)
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:4px;">
            <span style="color:#f87171;">•</span>
            <span style="color:#cbd5e1;">{{ $p->student->name }} — overdue ₱{{ number_format($p->amount,2) }} for {{ $p->month }} {{ $p->year }}</span>
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
@media(min-width:768px){
    .stats-grid{grid-template-columns:repeat(4,1fr)!important;}
    .two-col{grid-template-columns:repeat(2,1fr)!important;}
}
@media(max-width:767px){
    .two-col{grid-template-columns:1fr!important;}
}
</style>
@endsection