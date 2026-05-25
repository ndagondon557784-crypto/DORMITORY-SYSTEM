<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'DormSystem ND')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --barca-blue: #004D98;
            --barca-maroon: #A50044;
            --barca-gold: #EDBB00;
            --barca-dark: #0a1628;
            --barca-surface: #0f1f3d;
            --barca-card: #162447;
            --barca-border: #1e3560;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: var(--barca-dark); color: #e2e8f0; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--barca-dark); }
        ::-webkit-scrollbar-thumb { background: var(--barca-border); border-radius: 3px; }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; font-size:14px; font-weight:500; color:#94a3b8; transition:all .2s; text-decoration:none; }
        .sidebar-link:hover { color:#fff; background:rgba(255,255,255,.05); }
        .sidebar-link.active { color:#fff; background:linear-gradient(135deg,var(--barca-blue),var(--barca-maroon)); box-shadow:0 2px 8px rgba(0,77,152,.4); }
        .card { background:var(--barca-card); border:1px solid var(--barca-border); border-radius:12px; }
        .btn-primary { background:linear-gradient(135deg,var(--barca-blue),var(--barca-maroon)); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:opacity .2s; }
        .btn-primary:hover { opacity:.9; }
        .btn-outline { background:transparent; color:#94a3b8; border:1px solid var(--barca-border); padding:8px 16px; border-radius:8px; font-size:14px; font-weight:500; cursor:pointer; transition:all .2s; }
        .btn-outline:hover { color:#fff; border-color:#fff; }
        .input { width:100%; padding:10px 12px; background:var(--barca-surface); border:1px solid var(--barca-border); border-radius:8px; color:#fff; font-size:14px; outline:none; transition:border-color .2s; }
        .input:focus { border-color:var(--barca-blue); }
        .input::placeholder { color:#475569; }
        select.input option { background:var(--barca-surface); color:#fff; }
        .badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:600; }
        .badge-success { background:rgba(16,185,129,.15); color:#34d399; border:1px solid rgba(16,185,129,.3); }
        .badge-warning { background:rgba(245,158,11,.15); color:#fbbf24; border:1px solid rgba(245,158,11,.3); }
        .badge-error   { background:rgba(239,68,68,.15);  color:#f87171; border:1px solid rgba(239,68,68,.3); }
        .badge-info    { background:rgba(59,130,246,.15); color:#60a5fa; border:1px solid rgba(59,130,246,.3); }
        .badge-default { background:rgba(148,163,184,.15); color:#94a3b8; border:1px solid rgba(148,163,184,.3); }
        .table-row:hover { background:rgba(255,255,255,.03); }
        .modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);z-index:50;display:flex;align-items:center;justify-content:center;padding:16px; }
        .modal-box { background:var(--barca-card);border:1px solid var(--barca-border);border-radius:16px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto; }
        .alert-success { background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);color:#34d399;padding:10px 16px;border-radius:8px;font-size:14px;margin-bottom:16px; }
        .alert-error   { background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#f87171;padding:10px 16px;border-radius:8px;font-size:14px;margin-bottom:16px; }
    </style>
    @stack('styles')
</head>
<body>
<div style="display:flex;height:100vh;overflow:hidden;">

    <!-- Mobile overlay -->
    <div id="sidebar-overlay" onclick="closeSidebar()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:20;"></div>

    <!-- Sidebar -->
    <aside id="sidebar" style="position:fixed;inset-y:0;left:0;z-index:30;width:256px;display:flex;flex-direction:column;background:var(--barca-surface);border-right:1px solid var(--barca-border);transform:translateX(-256px);transition:transform .3s;" class="lg-sidebar">
        <!-- Logo -->
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--barca-border);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;background:var(--barca-blue);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-building" style="color:#fff;font-size:16px;"></i>
                </div>
                <div>
                    <p style="font-weight:700;color:#fff;font-size:14px;line-height:1.2;">DormSystem</p>
                    <p style="font-size:11px;color:var(--barca-gold);">ND Campus</p>
                </div>
            </div>
            <button onclick="closeSidebar()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px;" class="lg-hidden"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Nav -->
        <nav style="flex:1;padding:16px 12px;overflow-y:auto;display:flex;flex-direction:column;gap:4px;">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge-high" style="width:18px;"></i> Dashboard</a>
            <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}"><i class="fa-solid fa-users" style="width:18px;"></i> Students</a>
            <a href="{{ route('rooms.index') }}" class="sidebar-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}"><i class="fa-solid fa-bed" style="width:18px;"></i> Rooms</a>
            <a href="{{ route('allocations.index') }}" class="sidebar-link {{ request()->routeIs('allocations.*') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-list" style="width:18px;"></i> Allocations</a>
            <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}"><i class="fa-solid fa-credit-card" style="width:18px;"></i> Payments</a>
            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-bar" style="width:18px;"></i> Reports</a>
        </nav>

        <!-- Footer -->
        <div style="padding:12px;border-top:1px solid var(--barca-border);">
            <div style="background:var(--barca-card);border-radius:8px;padding:10px 12px;margin-bottom:8px;">
                <p style="font-size:11px;color:#64748b;">Logged in as</p>
                <p style="font-size:13px;font-weight:600;color:#fff;">{{ auth()->user()->name }}</p>
                <p style="font-size:11px;color:var(--barca-gold);text-transform:capitalize;">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link" style="width:100%;border:none;cursor:pointer;background:none;color:#94a3b8;">
                    <i class="fa-solid fa-right-from-bracket" style="width:18px;"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden;margin-left:0;" id="main-content">
        <!-- Header -->
        <header style="display:flex;align-items:center;justify-content:space-between;padding:12px 24px;background:var(--barca-surface);border-bottom:1px solid var(--barca-border);flex-shrink:0;">
            <button onclick="openSidebar()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:20px;padding:4px;" id="menu-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--barca-blue),var(--barca-maroon));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div style="display:none;" class="md-block">
                    <p style="font-size:13px;font-weight:600;color:#fff;line-height:1.2;">{{ auth()->user()->name }}</p>
                    <p style="font-size:11px;color:#64748b;text-transform:capitalize;">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main style="flex:1;overflow-y:auto;padding:24px;">
            @if(session('success'))
                <div class="alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<style>
@media(min-width:1024px){
    #sidebar{transform:translateX(0)!important;}
    #main-content{margin-left:256px!important;}
    #menu-btn{display:none!important;}
    #sidebar-overlay{display:none!important;}
}
@media(min-width:768px){ .md-block{display:block!important;} }
</style>

<script>
function openSidebar(){
    document.getElementById('sidebar').style.transform='translateX(0)';
    document.getElementById('sidebar-overlay').style.display='block';
}
function closeSidebar(){
    document.getElementById('sidebar').style.transform='translateX(-256px)';
    document.getElementById('sidebar-overlay').style.display='none';
}
function openModal(id){ document.getElementById(id).style.display='flex'; }
function closeModal(id){ document.getElementById(id).style.display='none'; }
// Close modal on outside click
document.addEventListener('click', function(e){
    if(e.target.classList.contains('modal-overlay')){
        e.target.style.display='none';
    }
});
// Auto-hide alerts
setTimeout(()=>{ document.querySelectorAll('.alert-success,.alert-error').forEach(el=>el.style.display='none'); }, 4000);
</script>
@stack('scripts')
</body>
</html>