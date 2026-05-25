@extends('layouts.app')
@section('title','Reports')
@section('content')

<div style="display:flex;flex-direction:column;gap:20px;">
    <div>
        <h1 style="font-size:20px;font-weight:700;color:#fff;">Reports</h1>
        <p style="font-size:13px;color:#64748b;margin-top:2px;">System analytics and export tools</p>
    </div>

    <!-- Export buttons -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
        @foreach([['Student Report','fa-users','var(--barca-blue)','students'],['Room Occupancy','fa-bed','#10b981','rooms'],['Payment Report','fa-credit-card','var(--barca-maroon)','payments'],['Full Summary','fa-chart-bar','var(--barca-gold)','summary']] as [$label,$icon,$color,$type])
        <a href="{{ route('reports.export',$type) }}" class="card" style="display:flex;align-items:center;gap:12px;padding:16px;text-decoration:none;transition:transform .2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width:42px;height:42px;border-radius:10px;background:{{ $color }}22;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid {{ $icon }}" style="color:{{ $color }};font-size:18px;"></i>
            </div>
            <div>
                <p style="font-size:13px;font-weight:500;color:#fff;">{{ $label }}</p>
                <p style="font-size:11px;color:#64748b;margin-top:2px;display:flex;align-items:center;gap:4px;"><i class="fa-solid fa-download" style="font-size:10px;"></i> Export PDF/Excel</p>
            </div>
        </a>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="two-col">
        <!-- Occupancy -->
        <div class="card" style="overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);">
                <span style="font-size:14px;font-weight:600;color:#fff;">Room Occupancy</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:12px;">
                @foreach($rooms as $room)
                @php $pct = $room->capacity > 0 ? round(($room->occupied/$room->capacity)*100) : 0; @endphp
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:13px;font-weight:500;color:#fff;">Room {{ $room->room_number }}</span>
                            <span class="badge badge-default">{{ $room->type }}</span>
                            <span class="badge badge-{{ $room->status==='Available' ? 'success' : ($room->status==='Full' ? 'error' : 'warning') }}">{{ $room->status }}</span>
                        </div>
                        <span style="font-size:11px;color:#64748b;">{{ $room->occupied }}/{{ $room->capacity }}</span>
                    </div>
                    <div style="height:6px;background:#1e3560;border-radius:999px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $room->status==='Full' ? 'var(--barca-maroon)' : 'var(--barca-blue)' }};border-radius:999px;"></div>
                    </div>
                </div>
                @endforeach
                <div style="padding-top:12px;border-top:1px solid var(--barca-border);display:flex;justify-content:space-between;">
                    <span style="font-size:13px;color:#64748b;">Overall Rate</span>
                    <span style="font-size:14px;font-weight:700;color:var(--barca-gold);">{{ $data['occupancy_rate'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="card" style="overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);">
                <span style="font-size:14px;font-weight:600;color:#fff;">Payment Summary</span>
            </div>
            <div style="padding:20px;display:flex;flex-direction:column;gap:8px;">
                @foreach([['Total Collected','₱'.number_format($data['total_collected'],2),'#34d399'],['Total Pending','₱'.number_format($data['total_pending'],2),'#fbbf24'],['Total Overdue','₱'.number_format($data['total_overdue'],2),'#f87171'],['Collection Rate',$data['collection_rate'].'%','var(--barca-gold)']] as [$label,$val,$color])
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--barca-surface);border-radius:8px;">
                    <span style="font-size:13px;color:#94a3b8;">{{ $label }}</span>
                    <span style="font-size:13px;font-weight:700;color:{{ $color }};">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Students by Course -->
        <div class="card" style="overflow:hidden;grid-column:1/-1;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--barca-border);">
                <span style="font-size:14px;font-weight:600;color:#fff;">Students by Course</span>
            </div>
            <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:8px;">
                @foreach($courseBreakdown as $c)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--barca-surface);border-radius:8px;">
                    <span style="font-size:13px;color:#cbd5e1;">{{ $c->course }}</span>
                    <span style="font-size:13px;font-weight:700;color:#fff;">{{ $c->total }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
@media(max-width:767px){ .two-col{grid-template-columns:1fr!important;} }
</style>
@endsection