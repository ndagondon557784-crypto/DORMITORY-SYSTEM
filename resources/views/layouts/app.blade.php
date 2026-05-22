<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Barca Academy Dormitory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display:none !important; }
        .nav-link {
            display:flex; align-items:center; gap:10px;
            padding:9px 16px; border-radius:10px;
            font-size:.875rem; font-weight:500;
            color:#bfdbfe; transition:all .15s ease; text-decoration:none;
        }
        .nav-link:hover { background:rgba(237,187,0,.15); color:#EDBB00; }
        .nav-link.active {
            background:rgba(237,187,0,.2); color:#EDBB00;
            border-left:3px solid #EDBB00; padding-left:13px;
        }
        ::-webkit-scrollbar { width:5px; }
        ::-webkit-scrollbar-thumb { background:#334155; border-radius:3px; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased">

<!-- TOP NAV -->
<header class="bg-[#004D98] text-white shadow-xl fixed top-0 left-0 right-0 z-50 h-16">
    <div class="flex items-center justify-between h-full px-4 lg:px-6">

        <div class="flex items-center gap-3">
            <button x-data @click="$dispatch('toggle-sidebar')"
                    class="lg:hidden p-2 rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <svg width="36" height="36" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="30" cy="30" rx="30" ry="30" fill="#EDBB00"/>
                    <ellipse cx="30" cy="30" rx="24" ry="24" fill="#004D98"/>
                    <rect x="10" y="10" width="40" height="40" rx="5" fill="#A50044"/>
                    <text x="30" y="38" text-anchor="middle" font-family="Georgia,serif" font-weight="bold" font-size="22" fill="#EDBB00">ND</text>
                </svg>
                <div class="hidden sm:block leading-tight">
                    <p class="font-extrabold text-sm">Barca Academy Dormitory</p>
                    <p class="text-blue-300 text-[10px]">Room Allocation System</p>
                </div>
            </a>
        </div>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open=!open"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-blue-700 transition text-sm">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                    {{ auth()->user()->isAdmin() ? 'bg-[#EDBB00] text-[#004D98]' : 'bg-[#A50044] text-white' }}">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="hidden md:block max-w-32 truncate text-sm">{{ auth()->user()->name }}</span>
                <svg class="w-3 h-3 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" @click.away="open=false" x-cloak x-transition
                 class="absolute right-0 top-12 bg-white text-gray-700 shadow-2xl rounded-2xl w-52 py-2 border border-gray-100 z-50">
                <div class="px-4 py-2 border-b border-gray-100 mb-1">
                    <p class="font-semibold text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    <span class="mt-1 inline-block text-[10px] font-bold px-2 py-0.5 rounded-full
                        {{ auth()->user()->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50">🏠 Dashboard</a>
                @if(auth()->user()->isStudent())
                    <a href="{{ route('student.portal') }}"            class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50">👤 My Profile</a>
                    <a href="{{ route('student.room') }}"              class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50">🛏 My Room</a>
                    <a href="{{ route('student.apply') }}"             class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50">🏠 Apply for Room</a>
                    <a href="{{ route('student.application.status') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50">📋 My Applications</a>
                    <a href="{{ route('student.profile.edit') }}"      class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50">✏️ Edit Profile</a>
                @endif
                <div class="border-t border-gray-100 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- BODY WRAPPER -->
<div class="flex pt-16 min-h-screen"
     x-data="{ sidebarOpen: false }"
     @toggle-sidebar.window="sidebarOpen=!sidebarOpen">

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:sticky lg:top-16 left-0 top-16 bottom-0 w-64 bg-[#004D98] text-white
                  overflow-y-auto z-40 transition-transform duration-300 shadow-2xl
                  flex flex-col lg:h-[calc(100vh-4rem)]">

        <div class="flex-1 py-5 px-3">

            @if(auth()->user()->isAdmin())
            {{-- ═══ ADMIN SIDEBAR ═══ --}}
            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-3 px-3">Admin Panel</p>
            <nav class="space-y-0.5">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    🏠 Dashboard
                </a>
                <a href="{{ route('admin.rooms.index') }}"
                   class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                    🚪 Rooms
                </a>
                <a href="{{ route('admin.students.index') }}"
                   class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    👨‍🎓 Students
                </a>
                <a href="{{ route('admin.allocations.index') }}"
                   class="nav-link {{ request()->routeIs('admin.allocations.*') ? 'active' : '' }}">
                    📋 Allocations
                </a>
                <a href="{{ route('admin.applications.index') }}"
                   class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    📬 Applications
                    @php
                        try { $pc = \App\Models\Application::where('status','pending')->count(); } catch(\Exception $e) { $pc = 0; }
                    @endphp
                    @if($pc > 0)
                    <span class="ml-auto text-[10px] bg-[#A50044] text-white rounded-full px-1.5 py-0.5 font-bold">{{ $pc }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.admins.index') }}"
                   class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                    🛡 Administrators
                </a>
                <div class="pt-3 pb-1">
                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest px-3">Analytics</p>
                </div>
                <a href="{{ route('admin.reports') }}"
                   class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    📊 Reports
                </a>
                <a href="{{ route('admin.activity-logs') }}"
                   class="nav-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                    📜 Activity Logs
                </a>
            </nav>

            @else
            {{-- ═══ STUDENT SIDEBAR ═══ --}}
            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-3 px-3">Student Menu</p>
            <nav class="space-y-0.5">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    🏠 Dashboard
                </a>
                <a href="{{ route('student.portal') }}"
                   class="nav-link {{ request()->routeIs('student.portal') ? 'active' : '' }}">
                    👤 My Profile
                </a>
                <a href="{{ route('student.room') }}"
                   class="nav-link {{ request()->routeIs('student.room') ? 'active' : '' }}">
                    🛏 My Room
                </a>
                <a href="{{ route('student.apply') }}"
                   class="nav-link {{ request()->routeIs('student.apply') ? 'active' : '' }}">
                    🏠 Apply for Room
                </a>
                <a href="{{ route('student.application.status') }}"
                   class="nav-link {{ request()->routeIs('student.application.status') ? 'active' : '' }}">
                    📋 My Applications
                </a>
                <a href="{{ route('student.profile.edit') }}"
                   class="nav-link {{ request()->routeIs('student.profile.*') ? 'active' : '' }}">
                    ✏️ Edit Profile
                </a>
            </nav>
            @endif

        </div>

        <div class="px-4 py-3 border-t border-blue-800">
            <div class="flex items-center gap-2 justify-center">
                <svg width="20" height="20" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="30" cy="30" rx="30" ry="30" fill="#EDBB00"/>
                    <ellipse cx="30" cy="30" rx="24" ry="24" fill="#004D98"/>
                    <rect x="10" y="10" width="40" height="40" rx="5" fill="#A50044"/>
                    <text x="30" y="38" text-anchor="middle" font-family="Georgia,serif" font-weight="bold" font-size="22" fill="#EDBB00">ND</text>
                </svg>
                <p class="text-[10px] text-blue-400">Barca Academy © {{ date('Y') }}</p>
            </div>
        </div>
    </aside>

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen=false" x-cloak
         class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 overflow-x-hidden">

        @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3.5 text-sm"
             x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)" x-transition>
            ✅ {{ session('success') }}
            <button @click="show=false" class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-4 py-3.5 text-sm"
             x-data="{show:true}" x-show="show" x-transition>
            ❌ {{ session('error') }}
            <button @click="show=false" class="ml-auto text-red-400 hover:text-red-600">✕</button>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-4 py-3.5 text-sm">
            <p class="font-bold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>