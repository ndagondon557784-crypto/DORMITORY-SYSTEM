@extends('layouts.app')
@section('title','Rooms')
@section('content')

<div style="display:flex;flex-direction:column;gap:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:20px;font-weight:700;color:#fff;">Rooms</h1>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">{{ $rooms->count() }} rooms total</p>
        </div>
        <button class="btn-primary" onclick="openModal('add-room-modal')">
            <i class="fa-solid fa-plus"></i> Add Room
        </button>
    </div>

    <!-- Filter pills -->
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        @foreach(['All','Available','Full','Maintenance'] as $s)
        <a href="?status={{ $s }}" style="padding:6px 16px;border-radius:999px;font-size:12px;font-weight:500;text-decoration:none;border:1px solid var(--barca-border);transition:all .2s;{{ request('status',$s)===$s ? 'background:var(--barca-blue);color:#fff;border-color:var(--barca-blue);' : 'background:var(--barca-card);color:#94a3b8;' }}">
            {{ $s }}
        </a>
        @endforeach
    </div>

    <!-- Room cards grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
        @forelse($rooms as $room)
        @php
            $pct = $room->capacity > 0 ? round(($room->occupied/$room->capacity)*100) : 0;
            $statusColor = $room->status==='Available' ? '#34d399' : ($room->status==='Full' ? '#f87171' : '#fbbf24');
            $statusBg = $room->status==='Available' ? 'rgba(16,185,129,.15)' : ($room->status==='Full' ? 'rgba(239,68,68,.15)' : 'rgba(245,158,11,.15)');
            $amenities = is_array($room->amenities) ? $room->amenities : json_decode($room->amenities ?? '[]', true);
        @endphp
        <div class="card" style="padding:20px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;border-radius:10px;background:{{ $statusBg }};display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-bed" style="color:{{ $statusColor }};font-size:18px;"></i>
                    </div>
                    <div>
                        <p style="font-weight:600;color:#fff;font-size:15px;">Room {{ $room->room_number }}</p>
                        <p style="font-size:11px;color:#64748b;">Floor {{ $room->floor }} • {{ $room->type }}</p>
                    </div>
                </div>
                <span class="badge badge-{{ $room->status==='Available' ? 'success' : ($room->status==='Full' ? 'error' : 'warning') }}">{{ $room->status }}</span>
            </div>

            <!-- Occupancy bar -->
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-size:11px;color:#64748b;">Occupancy</span>
                    <span style="font-size:12px;font-weight:500;color:#fff;">{{ $room->occupied }}/{{ $room->capacity }}</span>
                </div>
                <div style="height:6px;background:#1e3560;border-radius:999px;overflow:hidden;">
                    <div style="height:100%;width:{{ $pct }}%;background:{{ $room->status==='Full' ? 'var(--barca-maroon)' : 'var(--barca-blue)' }};border-radius:999px;"></div>
                </div>
            </div>

            <!-- Amenities -->
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;">
                @foreach(array_slice($amenities ?? [], 0, 3) as $a)
                <span style="padding:2px 8px;background:var(--barca-surface);border:1px solid var(--barca-border);border-radius:6px;font-size:11px;color:#94a3b8;">{{ $a }}</span>
                @endforeach
                @if(count($amenities ?? []) > 3)
                <span style="font-size:11px;color:#475569;">+{{ count($amenities)-3 }} more</span>
                @endif
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid var(--barca-border);">
                <span style="font-size:14px;font-weight:700;color:var(--barca-gold);">₱{{ number_format($room->price_per_month,2) }}/mo</span>
                <div style="display:flex;gap:6px;">
                    <button onclick="openEditRoom({{ $room->id }}, '{{ $room->room_number }}', {{ $room->floor }}, '{{ $room->type }}', {{ $room->price_per_month }}, '{{ $room->status }}')"
                        style="padding:6px;border-radius:6px;background:none;border:none;color:#64748b;cursor:pointer;" onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.color='#64748b';this.style.background='none'">
                        <i class="fa-solid fa-pen-to-square" style="font-size:13px;"></i>
                    </button>
                    <form method="POST" action="{{ route('rooms.destroy',$room) }}" onsubmit="return confirm('Delete this room?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="padding:6px;border-radius:6px;background:none;border:none;color:#64748b;cursor:pointer;" onmouseover="this.style.color='#f87171';this.style.background='rgba(239,68,68,.1)'" onmouseout="this.style.color='#64748b';this.style.background='none'">
                            <i class="fa-solid fa-trash" style="font-size:13px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:48px;color:#475569;font-size:13px;">No rooms found.</div>
        @endforelse
    </div>
</div>

<!-- Add Room Modal -->
<div id="add-room-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">Add New Room</h2>
            <button onclick="closeModal('add-room-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" action="{{ route('rooms.store') }}" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Room Number *</label>
                    <input type="text" name="room_number" required placeholder="e.g. 101" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Floor *</label>
                    <input type="number" name="floor" required min="1" value="1" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Room Type *</label>
                    <select name="type" class="input"><option>Single</option><option>Double</option><option>Triple</option><option>Quad</option></select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Price/Month (₱) *</label>
                    <input type="number" name="price_per_month" required min="0" placeholder="3500" class="input" />
                </div>
                <div style="grid-column:1/-1;">
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Status *</label>
                    <select name="status" class="input"><option>Available</option><option>Full</option><option>Maintenance</option></select>
                </div>
                <div style="grid-column:1/-1;">
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:8px;">Amenities</label>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach(['WiFi','AC','Private Bathroom','Shared Bathroom','Study Desk','Refrigerator','Wardrobe'] as $a)
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#94a3b8;cursor:pointer;">
                            <input type="checkbox" name="amenities[]" value="{{ $a }}" style="accent-color:var(--barca-blue);" /> {{ $a }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="button" onclick="closeModal('add-room-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Add Room</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Room Modal -->
<div id="edit-room-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">Edit Room</h2>
            <button onclick="closeModal('edit-room-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" id="edit-room-form" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Room Number *</label>
                    <input type="text" name="room_number" id="edit-room-number" required class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Floor *</label>
                    <input type="number" name="floor" id="edit-room-floor" required min="1" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Room Type *</label>
                    <select name="type" id="edit-room-type" class="input"><option>Single</option><option>Double</option><option>Triple</option><option>Quad</option></select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Price/Month (₱) *</label>
                    <input type="number" name="price_per_month" id="edit-room-price" required class="input" />
                </div>
                <div style="grid-column:1/-1;">
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Status *</label>
                    <select name="status" id="edit-room-status" class="input"><option>Available</option><option>Full</option><option>Maintenance</option></select>
                </div>
            </div>
            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="button" onclick="closeModal('edit-room-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditRoom(id, num, floor, type, price, status) {
    document.getElementById('edit-room-form').action = '/rooms/' + id;
    document.getElementById('edit-room-number').value = num;
    document.getElementById('edit-room-floor').value = floor;
    document.getElementById('edit-room-type').value = type;
    document.getElementById('edit-room-price').value = price;
    document.getElementById('edit-room-status').value = status;
    openModal('edit-room-modal');
}
</script>
@endpush
@endsection