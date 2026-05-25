<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'DormSystem ND')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
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
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--barca-dark);
            color: #e2e8f0;
            -webkit-font-smoothing: antialiased;
        }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--barca-dark); }
        ::-webkit-scrollbar-thumb { background: var(--barca-border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--barca-blue); }

        /* Sidebar */
        #sidebar {
            position: fixed;
            inset-y: 0;
            left: 0;
            z-index: 30;
            width: 256px;
            display: flex;
            flex-direction: column;
            background: var(--barca-surface);
            border-right: 1px solid var(--barca-border);
            transform: translateX(-256px);
            transition: transform .3s ease;
        }
        #sidebar.open { transform: translateX(0); }
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.6);
            z-index: 20;
        }
        #main-content {
            margin-left: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left .3s ease;
        }
        @media(min-width: 1024px) {
            #sidebar { transform: translateX(0) !important; }
            #main-content { margin-left: 256px !important; }
            #menu-btn { display: none !important; }
            #sidebar-overlay { display: none !important; }
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            transition: all .2s;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
        }
        .sidebar-link:hover { color: #fff; background: rgba(255,255,255,.05); }
        .sidebar-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
            box-shadow: 0 2px 8px rgba(0,77,152,.4);
        }
        .sidebar-link i { width: 18px; text-align: center; }

        /* Cards / UI */
        .card {
            background: var(--barca-card);
            border: 1px solid var(--barca-border);
            border-radius: 12px;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity .2s;
            text-decoration: none;
        }
        .btn-primary-custom:hover { opacity: .9; color: #fff; }
        .btn-outline-custom {
            background: transparent;
            color: #94a3b8;
            border: 1px solid var(--barca-border);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-outline-custom:hover { color: #fff; border-color: #fff; }
        .input {
            width: 100%;
            padding: 10px 12px;
            background: var(--barca-surface);
            border: 1px solid var(--barca-border);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color .2s;
        }
        .input:focus { border-color: var(--barca-blue); }
        .input::placeholder { color: #475569; }
        select.input option { background: var(--barca-surface); color: #fff; }
        textarea.input { resize: vertical; }

        /* Badges */
        .badge-success { background: rgba(16,185,129,.15); color: #34d399; border: 1px solid rgba(16,185,129,.3); }
        .badge-warning { background: rgba(245,158,11,.15); color: #fbbf24; border: 1px solid rgba(245,158,11,.3); }
        .badge-error   { background: rgba(239,68,68,.15);  color: #f87171; border: 1px solid rgba(239,68,68,.3); }
        .badge-info    { background: rgba(59,130,246,.15); color: #60a5fa; border: 1px solid rgba(59,130,246,.3); }
        .badge-default { background: rgba(148,163,184,.15); color: #94a3b8; border: 1px solid rgba(148,163,184,.3); }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Table rows */
        .table-row { transition: background .15s; }
        .table-row:hover { background: rgba(255,255,255,.03); }

        /* Modals */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.7);
            backdrop-filter: blur(4px);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-box {
            background: var(--barca-card);
            border: 1px solid var(--barca-border);
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Alerts */
        .alert-success-custom {
            background: rgba(16,185,129,.1);
            border: 1px solid rgba(16,185,129,.2);
            color: #34d399;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .alert-error-custom {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.2);
            color: #f87171;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
        }

        /* Page header */
        #page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            background: var(--barca-surface);
            border-bottom: 1px solid var(--barca-border);
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Main area */
        #page-main { flex: 1; padding: 24px; overflow-y: auto; }

        /* btn alias */
        .btn-primary { background: linear-gradient(135deg,var(--barca-blue),var(--barca-maroon)); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:opacity .2s; text-decoration:none; }
        .btn-primary:hover { opacity:.9; color:#fff; }
        .btn-outline { background:transparent; color:#94a3b8; border:1px solid var(--barca-border); padding:8px 16px; border-radius:8px; font-size:14px; font-weight:500; cursor:pointer; transition:all .2s; }
        .btn-outline:hover { color:#fff; border-color:#fff; }
    </style>
    @stack('styles')
</head>
<body>

<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar">
    <!-- Logo -->
    <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--barca-border);">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;background:var(--barca-blue);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-building" style="color:#fff;font-size:16px;"></i>
            </div>
            <div>
                <p style="font-weight:700;color:#fff;font-size:14px;line-height:1.2;margin:0;">DormSystem</p>
                <p style="font-size:11px;color:var(--barca-gold);margin:0;">ND Campus</p>
            </div>
        </div>
        <button onclick="closeSidebar()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:18px;display:block;" id="close-sidebar-btn">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Nav links -->
    <nav style="flex:1;padding:16px 12px;overflow-y:auto;display:flex;flex-direction:column;gap:4px;">
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> Students
        </a>
        <a href="{{ route('rooms.index') }}" class="sidebar-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bed"></i> Rooms
        </a>
        <a href="{{ route('allocations.index') }}" class="sidebar-link {{ request()->routeIs('allocations.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i> Allocations
        </a>
        <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
            <i class="fa-solid fa-credit-card"></i> Payments
        </a>
        <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-bar"></i> Reports
        </a>
        <a href="{{ route('announcements.index') }}" class="sidebar-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bullhorn"></i> Announcements
        </a>
    </nav>

    <!-- User + logout -->
    <div style="padding:12px;border-top:1px solid var(--barca-border);">
        <div style="background:var(--barca-card);border-radius:8px;padding:10px 12px;margin-bottom:8px;">
            <p style="font-size:11px;color:#64748b;margin:0;">Logged in as</p>
            <p style="font-size:13px;font-weight:600;color:#fff;margin:2px 0 0;">{{ auth()->user()->name }}</p>
            <p style="font-size:11px;color:var(--barca-gold);margin:0;text-transform:capitalize;">{{ auth()->user()->role ?? 'staff' }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link" style="color:#94a3b8;">
                <i class="fa-solid fa-right-from-bracket"></i> Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- Main content -->
<div id="main-content">

    <!-- Header -->
    <header id="page-header">
        <button id="menu-btn" onclick="openSidebar()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:20px;padding:4px;">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--barca-blue),var(--barca-maroon));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(strrchr(auth()->user()->name ?? ' U', ' '), 1, 1)) }}
            </div>
            <div style="display:none;" class="d-md-block">
                <p style="font-size:13px;font-weight:600;color:#fff;line-height:1.2;margin:0;">{{ auth()->user()->name }}</p>
                <p style="font-size:11px;color:#64748b;text-transform:capitalize;margin:0;">{{ auth()->user()->role ?? 'staff' }}</p>
            </div>
        </div>
    </header>

    <!-- Flash messages -->
    <div style="padding:0 24px;margin-top:16px;">
        @if(session('success'))
            <div class="alert-success-custom">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-error-custom">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('info'))
            <div style="background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.2);color:#60a5fa;padding:10px 16px;border-radius:8px;font-size:14px;margin-bottom:16px;">
                <i class="fa-solid fa-circle-info me-1"></i> {{ session('info') }}
            </div>
        @endif
    </div>

    <!-- Page content -->
    <main id="page-main">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').style.display = 'block';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').style.display = 'none';
    }
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
        }
    });
    setTimeout(function() {
        document.querySelectorAll('.alert-success-custom, .alert-error-custom').forEach(function(el) {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(function() { el.style.display = 'none'; }, 500);
        });
    }, 4000);
</script>
@stack('scripts')
</body>
</html>