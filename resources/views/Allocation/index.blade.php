@extends('layouts.app')
@section('title','Allocations')
@section('content')

<div style="display:flex;flex-direction:column;gap:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:20px;font-weight:700;color:#fff;">Allocations</h1>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Room assignment management</p>
        </div>
        <button class="btn-primary" onclick="openModal('add-allocation-modal')">
            <i class="fa-solid fa-plus"></i> New Allocation
        </button>
    </div>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
        @foreach([['Active',$stats['active'],'#34d399'],['Transferred',$stats['transferred'],'#fbbf24'],['Vacated',$stats['vacated'],'#94a3b8']] as [$label,$val,$color])
        <div class="card" style="padding:16px;text-align:center;">
            <p style="font-size:24px;font-weight:700;color:{{ $color }};">{{ $val }}</p>
            <p style="font-size:12px;color:#64748b;margin-top:4px;">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    <!-- Search + Filter -->
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="fa-solid fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;font-size:13px;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student or room..." class="input" style="padding-left:36px;" />
        </div>
        @foreach(['All','Active','Transferred','Vacated'] as $s)
        <a href="?status={{ $s }}{{ request('search') ? '&search='.request('search') : '' }}"
           style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;border:1px solid var(--barca-border);transition:all .2s;{{ request('status',$s)===$s ? 'background:var(--barca-blue);color:#fff;' : 'background:var(--barca-card);color:#94a3b8;' }}">
            {{ $s }}
        </a>
        @endforeach
    </form>

    <!-- Table -->
    <div class="card" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--barca-border);">
                        @foreach(['Student','Room','Start Date','End Date','Status','Actions'] as $h)
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($allocations as $a)
                    <tr class="table-row" style="border-bottom:1px solid rgba(30,53,96,.4);">
                        <td style="padding:12px 16px;font-size:13px;font-weight:500;color:#fff;white-space:nowrap;">{{ $a->student->name }}</td>
                        <td style="padding:12px 16px;"><span class="badge badge-info">Room {{ $a->room->room_number }}</span></td>
                        <td style="padding:12px 16px;font-size:13px;color:#cbd5e1;white-space:nowrap;">{{ \Carbon\Carbon::parse($a->start_date)->format('M d, Y') }}</td>
                        <td style="padding:12px 16px;font-size:13px;color:#64748b;">{{ $a->end_date ? \Carbon\Carbon::parse($a->end_date)->format('M d, Y') : '—' }}</td>
                        <td style="padding:12px 16px;"><span class="badge badge-{{ $a->status==='Active' ? 'success' : ($a->status==='Vacated' ? 'default' : 'warning') }}">{{ $a->status }}</span></td>
                        <td style="padding:12px 16px;">
                            @if($a->status === 'Active')
                            <div style="display:flex;gap:6px;">
                                <form method="POST" action="{{ route('allocations.vacate',$a->id) }}" onsubmit="return confirm('Vacate this room?')">
                                    @csrf
                                    <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:500;color:#f87171;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);cursor:pointer;">
                                        <i class="fa-solid fa-door-open" style="font-size:11px;"></i> Vacate
                                    </button>
                                </form>
                                <button onclick="openTransferModal({{ $a->id }})" style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:500;color:#60a5fa;background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.2);cursor:pointer;">
                                    <i class="fa-solid fa-right-left" style="font-size:11px;"></i> Transfer
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="padding:48px;text-align:center;color:#475569;font-size:13px;">No allocations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($allocations->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--barca-border);">{{ $allocations->links() }}</div>
        @endif
    </div>
</div>

<!-- New Allocation Modal -->
<div id="add-allocation-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">New Room Allocation</h2>
            <button onclick="closeModal('add-allocation-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" action="{{ route('allocations.store') }}" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Select Student *</label>
                <select name="student_id" required class="input">
                    <option value="">-- Choose a student --</option>
                    @foreach($unallocatedStudents as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_id }})</option>
                    @endforeach
                </select>
                @if($unallocatedStudents->isEmpty())
                <p style="font-size:11px;color:#475569;margin-top:4px;">All active students are already allocated.</p>
                @endif
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Select Room *</label>
                <select name="room_id" required class="input">
                    <option value="">-- Choose a room --</option>
                    @foreach($availableRooms as $r)
                    <option value="{{ $r->id }}">Room {{ $r->room_number }} ({{ $r->type }}) — {{ $r->occupied }}/{{ $r->capacity }} occupied</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Start Date *</label>
                <input type="date" name="start_date" required class="input" value="{{ date('Y-m-d') }}" />
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Notes (optional)</label>
                <textarea name="notes" rows="2" class="input" placeholder="Any special notes..." style="resize:none;"></textarea>
            </div>
            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="button" onclick="closeModal('add-allocation-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Allocate Room</button>
            </div>
        </form>
    </div>
</div>

<!-- Transfer Modal -->
<div id="transfer-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">Transfer Student</h2>
            <button onclick="closeModal('transfer-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" id="transfer-form" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Transfer to Room *</label>
                <select name="new_room_id" required class="input">
                    <option value="">-- Choose destination room --</option>
                    @foreach($availableRooms as $r)
                    <option value="{{ $r->id }}">Room {{ $r->room_number }} ({{ $r->type }}) — {{ $r->occupied }}/{{ $r->capacity }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="button" onclick="closeModal('transfer-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Transfer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openTransferModal(id) {
    document.getElementById('transfer-form').action = '/allocations/' + id + '/transfer';
    openModal('transfer-modal');
}
</script>
@endpush
@endsection