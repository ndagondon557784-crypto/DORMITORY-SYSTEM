@extends('layouts.app')
@section('title','Students')
@section('content')

<div style="display:flex;flex-direction:column;gap:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:20px;font-weight:700;color:#fff;">Students</h1>
            <p style="font-size:13px;color:#64748b;margin-top:2px;">{{ $students->total() }} students total</p>
        </div>
        <button class="btn-primary" onclick="openModal('add-student-modal')">
            <i class="fa-solid fa-plus"></i> Add Student
        </button>
    </div>

    <!-- Filters -->
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:200px;">
            <i class="fa-solid fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;font-size:13px;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students..." class="input" style="padding-left:36px;" />
        </div>
        @foreach(['All','Active','Inactive','Graduated'] as $s)
        <a href="?status={{ $s }}{{ request('search') ? '&search='.request('search') : '' }}"
           style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;text-decoration:none;border:1px solid var(--barca-border);transition:all .2s;{{ request('status',$s)===$s && ($s!=='All' || !request('status')) ? 'background:var(--barca-blue);color:#fff;' : 'background:var(--barca-card);color:#94a3b8;' }}">
            {{ $s }}
        </a>
        @endforeach
        <button type="submit" class="btn-primary">Search</button>
    </form>

    <!-- Table -->
    <div class="card" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--barca-border);">
                        @foreach(['Student','ID','Course','Year','Gender','Room','Status','Actions'] as $h)
                        <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr class="table-row" style="border-bottom:1px solid rgba(30,53,96,.4);">
                        <td style="padding:12px 16px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--barca-blue),var(--barca-maroon));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(substr($student->name,0,1)).strtoupper(substr(strrchr($student->name,' '),1,1)) }}
                                </div>
                                <div>
                                    <p style="font-size:13px;font-weight:500;color:#fff;">{{ $student->name }}</p>
                                    <p style="font-size:11px;color:#64748b;">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="padding:12px 16px;font-size:11px;color:#64748b;white-space:nowrap;">{{ $student->student_id }}</td>
                        <td style="padding:12px 16px;font-size:13px;color:#cbd5e1;white-space:nowrap;">{{ $student->course }}</td>
                        <td style="padding:12px 16px;font-size:13px;color:#cbd5e1;">{{ $student->year_level }}</td>
                        <td style="padding:12px 16px;font-size:13px;color:#cbd5e1;">{{ $student->gender }}</td>
                        <td style="padding:12px 16px;">
                            @if($student->allocation)
                                <span class="badge badge-info">Room {{ $student->allocation->room->room_number }}</span>
                            @else
                                <span style="font-size:12px;color:#475569;">—</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            <span class="badge badge-{{ $student->status==='Active' ? 'success' : ($student->status==='Inactive' ? 'warning' : 'default') }}">{{ $student->status }}</span>
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="display:flex;gap:6px;">
                                <button onclick="openEditStudent({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->email }}', '{{ $student->phone }}', '{{ addslashes($student->course) }}', {{ $student->year_level }}, '{{ $student->gender }}', '{{ $student->status }}')"
                                    style="padding:6px;border-radius:6px;background:none;border:none;color:#64748b;cursor:pointer;transition:all .2s;" onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.color='#64748b';this.style.background='none'">
                                    <i class="fa-solid fa-pen-to-square" style="font-size:13px;"></i>
                                </button>
                                <form method="POST" action="{{ route('students.destroy',$student) }}" onsubmit="return confirm('Delete this student?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:6px;border-radius:6px;background:none;border:none;color:#64748b;cursor:pointer;transition:all .2s;" onmouseover="this.style.color='#f87171';this.style.background='rgba(239,68,68,.1)'" onmouseout="this.style.color='#64748b';this.style.background='none'">
                                        <i class="fa-solid fa-trash" style="font-size:13px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="padding:48px;text-align:center;color:#475569;font-size:13px;">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($students->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--barca-border);">{{ $students->links() }}</div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="add-student-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">Add New Student</h2>
            <button onclick="closeModal('add-student-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" action="{{ route('students.store') }}" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="grid-column:1/-1;">
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Full Name *</label>
                    <input type="text" name="name" required placeholder="Juan dela Cruz" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Email *</label>
                    <input type="email" name="email" required placeholder="student@ndmu.edu.ph" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Phone</label>
                    <input type="text" name="phone" placeholder="09171234567" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Course *</label>
                    <input type="text" name="course" required placeholder="BS Nursing" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Year Level *</label>
                    <select name="year_level" class="input">
                        @for($i=1;$i<=5;$i++) <option value="{{ $i }}">Year {{ $i }}</option> @endfor
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Gender *</label>
                    <select name="gender" class="input"><option>Male</option><option>Female</option></select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Status *</label>
                    <select name="status" class="input"><option>Active</option><option>Inactive</option><option>Graduated</option></select>
                </div>
            </div>
            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="button" onclick="closeModal('add-student-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Add Student</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-student-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="padding:16px 24px;border-bottom:1px solid var(--barca-border);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:15px;font-weight:600;color:#fff;">Edit Student</h2>
            <button onclick="closeModal('edit-student-modal')" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <form method="POST" id="edit-student-form" style="padding:24px;display:flex;flex-direction:column;gap:16px;">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="grid-column:1/-1;">
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Full Name *</label>
                    <input type="text" name="name" id="edit-name" required class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Email *</label>
                    <input type="email" name="email" id="edit-email" required class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Phone</label>
                    <input type="text" name="phone" id="edit-phone" class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Course *</label>
                    <input type="text" name="course" id="edit-course" required class="input" />
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Year Level *</label>
                    <select name="year_level" id="edit-year" class="input">
                        @for($i=1;$i<=5;$i++) <option value="{{ $i }}">Year {{ $i }}</option> @endfor
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Gender *</label>
                    <select name="gender" id="edit-gender" class="input"><option>Male</option><option>Female</option></select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Status *</label>
                    <select name="status" id="edit-status" class="input"><option>Active</option><option>Inactive</option><option>Graduated</option></select>
                </div>
            </div>
            <div style="display:flex;gap:12px;padding-top:8px;">
                <button type="button" onclick="closeModal('edit-student-modal')" class="btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditStudent(id, name, email, phone, course, year, gender, status) {
    document.getElementById('edit-student-form').action = '/students/' + id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-phone').value = phone;
    document.getElementById('edit-course').value = course;
    document.getElementById('edit-year').value = year;
    document.getElementById('edit-gender').value = gender;
    document.getElementById('edit-status').value = status;
    openModal('edit-student-modal');
}
</script>
@endpush
@endsection