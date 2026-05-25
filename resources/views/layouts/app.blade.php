<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: true }" x-init="$watch('darkMode', v => localStorage.setItem('darkMode', v))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DormiPro') — Dormitory Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --barca-navy:  #0a1628;
            --barca-blue:  #004d98;
            --barca-maroon:#a50044;
            --barca-gold:  #edbb00;
            --barca-light: #e8f0fe;
        }
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200; }
        .sidebar-link:hover { @apply bg-white/10 text-white; }
        .sidebar-link.active { @apply bg-white/20 text-white shadow-lg; }
        .card-glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .stat-card {
            @apply rounded-2xl p-6 text-white shadow-xl;
        }
        .btn-primary {
            @apply inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5;
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
        }
        .btn-secondary {
            @apply inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-gray-700 bg-white transition-all duration-200 hover:bg-gray-50;
        }
        .form-input {
            @apply w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all;
        }
        .table-row { @apply border-b border-gray-100 hover:bg-blue-50/30 transition-colors; }
        .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
        .badge-green  { @apply badge bg-emerald-100 text-emerald-700; }
        .badge-red    { @apply badge bg-red-100 text-red-700; }
        .badge-yellow { @apply badge bg-yellow-100 text-yellow-700; }
        .badge-blue   { @apply badge bg-blue-100 text-blue-700; }
        .badge-gray   { @apply badge bg-gray-100 text-gray-600; }
        .scrollbar-hidden::-webkit-scrollbar { display: none; }
        .dark .form-input { @apply bg-gray-800 border-gray-600 text-gray-100; }
        .dark body { @apply bg-gray-900 text-gray-100; }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900" x-cloak>

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 flex-shrink-0 overflow-y-auto scrollbar-hidden transition-transform duration-300"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        style="background: linear-gradient(180deg, #0a1628 0%, #004d98 60%, #a50044 100%);">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg" style="background: linear-gradient(135deg,#edbb00,#a50044)">D</div>
            <div>
                <div class="text-white font-bold text-lg leading-tight">DormíPro</div>
                <div class="text-white/50 text-xs">Room Allocation System</div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="px-6 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-yellow-400">
                @else
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white" style="background:var(--barca-maroon)">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>
                @endif
                <div class="overflow-hidden">
                    <div class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-white/50 text-xs">{{ auth()->user()->role?->display_name }}</div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="px-4 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            @can('admin,staff')
            <div class="pt-3 pb-1 px-4 text-xs font-semibold text-white/30 uppercase tracking-widest">Management</div>

            <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Students
            </a>

            <a href="{{ route('rooms.index') }}" class="sidebar-link {{ request()->routeIs('rooms.*') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Rooms
            </a>

            <a href="{{ route('allocations.index') }}" class="sidebar-link {{ request()->routeIs('allocations.*') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Allocations
            </a>

            <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Payments
            </a>

            <div class="pt-3 pb-1 px-4 text-xs font-semibold text-white/30 uppercase tracking-widest">Reports</div>

            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Reports
            </a>
            @endcan

            <a href="{{ route('announcements.index') }}" class="sidebar-link {{ request()->routeIs('announcements.*') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                Announcements
            </a>

            @if(auth()->user()->isAdmin())
            <div class="pt-3 pb-1 px-4 text-xs font-semibold text-white/30 uppercase tracking-widest">Admin</div>
            <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </a>
            @endif
        </nav>

        {{-- Bottom --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link text-white/70 w-full hover:bg-red-500/20 hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden" :class="sidebarOpen ? 'lg:pl-64' : 'pl-0'">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm z-40 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                    <div class="text-xs text-gray-400">@yield('breadcrumb', 'Home')</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Dark Mode --}}
                <button @click="darkMode = !darkMode" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                {{-- Profile --}}
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:var(--barca-maroon)">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 hidden sm:block">{{ auth()->user()->name }}</span>
                </a>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mx-6 mt-4 flex items-center gap-3 px-5 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
            <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mx-6 mt-4 flex items-center gap-3 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium shadow-sm">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
            <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">✕</button>
        </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>