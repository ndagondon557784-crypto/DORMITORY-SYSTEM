@extends('layouts.app')
@section('title','Payments')
@section('content')

<div style="display:flex;flex-direction:column;gap:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:20px;font-weight:700;color:#fff;">Payments</h1>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">Monthly billing records</p>
        </div>
        <button class="btn-primary" onclick="openModal('add-payment-modal')">
            <i class="fa-solid fa-plus"></i> Add Payment
        </button>
    </div>

    <!-- Summary -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
        @foreach([['Collected','₱'.number_format($summary['collected'],2),'#34d399','rgba(16,185,129,.08)','rgba(16,185,129,.2)'],['Pending','₱'.number_format($summary['pending'],2),'#fbbf24','rgba(245,158,11,.08)','rgba(245,158,11,.2)'],['Overdue','₱'.number_format($summary['overdue'],2),'#f87171','rgba(239,68,68,.08)','rgba(239,68,68,.2)']] as [$label,$val,$color,$bg,$border])
        <div style="background:{{ $bg }};border:1px solid {{ $border }};border-radius:12px;padding:16px;">
            <p style="font-size:11px;color:#64748b;margin-bottom:4px;">{{ $label }}</p>
            <p style="font-size:18px;font-weight:700;color:{{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <!-- Filters -->
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="fa-solid fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;font-size:13px;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student..." class="input" style="padding-left:36px;" />
        </div>
        @foreach(['All','Paid','Pending','Overdue'] as $s)
        <a href="?status={{ $s }}{{ request('search') ? '&search='.request('search') : '' }}"
           style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;border:1px solid var(--barca-border);{{ request('status',$s)===$s ? 'background:var(--barca-blue);color:#fff;' : 'background:var(--barca-card);color:#94a3b8;' }}">
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
                        @foreach(['Student','Room','Period','Amount','Status','Paid Date','Actions'] as $h)
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr class="table-row" style="border-bottom:1px solid rgba(30,53,96,.4);">
                        <td style="padding:12px 16px;font-size:13px;font-weight:500;color:#fff;white-space:nowrap;">{{ $p->student->name }}</td>
                        <td style="padding:12px 16px;"><span class="badge badge-info">Room {{ $p->room->room_number }}</span></td>
                        <td style="padding:12px 16px;font-size:13px;color:#cbd5e1;white-space:nowrap;">{{ $p->month }} {{ $p->year }}</td>
                        <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#fff;">₱{{ number_format($p->amount,2) }}</td>
                        <td style="padding:12px 16px;"><span class="badge badge-{{ $p->status==='Paid' ? 'success' : ($p->status==='Pending' ? 'warning' : 'error') }}">{{ $p->status }}</span></td>
                        <td style="padding:12px 16px;font-size:13px;color:#64748b;">{{ $p->paid_date ? \Carbon\Carbon::parse($p->paid_date)->format('M d, Y') : '—' }}</td>
                        <td style="padding:12px 16px;">
                            <div style="display:flex;gap:6px;">
                                @if($p->status !== 'Paid')
                                <form method="POST" action="{{ route('payments.markPaid',$p->id) }}">
                                    @csrf
                                    <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:500;color:#34d399;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);cursor:pointer;white-space:nowrap;">
                                        <i class="fa-solid fa-circle-check" style="font-size:11px;"></i> Mark Paid
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('payments.destroy',$p) }}" onsubmit="return confirm('Delete this payment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px;border-radius:6px;background:none;border:none;color:#64748b;cursor:pointer;" onmouseover="this.style.color='#f87171';this.style.background='rgba(239,68,68,.1)'" onmouseout="this.style.color='#64748b';this.style.background='none'">
                                        <i class="fa-solid fa-trash" style="font-size:12px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="padding:48px;text-align:center;color:#475569;font-size:13px;">No payment records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--barca-border);">{{ $payments->links() }}</div>
        @endif
    </div>
</div>

<!-- Add Payment Modal -->
<div id="add-payment-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">Add Payment Record</h2>
            <button onclick="closeModal('add-payment-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" action="{{ route('payments.store') }}" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Student *</label>
                <select name="student_id" required class="input" id="payment-student-select" onchange="fillRoomFromStudent(this)">
                    <option value="">-- Choose student --</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}" data-room="{{ $s->allocation?->room_id }}" data-price="{{ $s->allocation?->room?->price_per_month }}">
                        {{ $s->name }} — {{ $s->allocation ? 'Room '.$s->allocation->room->room_number : 'No room' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="room_id" id="payment-room-id" />
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Amount (₱) *</label>
                    <input type="number" name="amount" id="payment-amount" required min="1" placeholder="3500" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Month *</label>
                    <select name="month" class="input">
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                        <option {{ $m === date('F') ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Year *</label>
                    <input type="number" name="year" required value="{{ date('Y') }}" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Status *</label>
                    <select name="status" class="input"><option>Pending</option><option>Paid</option><option>Overdue</option></select>
                </div>
            </div>
            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="button" onclick="closeModal('add-payment-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Add Record</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function fillRoomFromStudent(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('payment-room-id').value = opt.dataset.room || '';
    document.getElementById('payment-amount').value = opt.dataset.price || '';
}
</script>
@endpush
@endsection