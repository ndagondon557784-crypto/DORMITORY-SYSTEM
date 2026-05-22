<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DormMS') — DormMS</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200; }
        .sidebar-link:hover { @apply bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300; }
        .sidebar-link.active { @apply bg-primary-100 text-primary-700 font-semibold dark:bg-primary-900/50 dark:text-primary-300; }
        .badge-success { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400; }
        .badge-danger { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400; }
        .badge-warning { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400; }
        .badge-info { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400; }
        .badge-secondary { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300; }
        .form-input { @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 dark:text-white transition-colors; }
        .form-label { @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1; }
        .btn-primary { @apply inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2; }
        .btn-secondary { @apply inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors; }
        .btn-danger { @apply inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors; }
        .card { @apply bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700; }
        .stat-card { @apply card p-6 flex items-center gap-4; }
        .table-container { @apply overflow-x-auto; }
        .table { @apply min-w-full divide-y divide-gray-200 dark:divide-gray-700; }
        .table thead th { @apply px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-900/50; }
        .table tbody tr { @apply hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors; }
        .table tbody td { @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100; }
        .page-header { @apply flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6; }
        .breadcrumb { @apply flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mb-2; }
        .breadcrumb a { @apply hover:text-primary-600 dark:hover:text-primary-400 transition-colors; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }

        /* Sidebar transition */
        .sidebar-transition { transition: transform 0.3s ease, width 0.3s ease; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300"
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
      @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay (mobile) -->
        <div x-show="sidebarOpen && window.innerWidth < 1024"
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black/50 lg:hidden">
        </div>

        <!-- Sidebar -->
        <aside x-show="sidebarOpen"
               x-cloak
               class="fixed lg:relative z-30 flex flex-col w-64 h-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 sidebar-transition flex-shrink-0"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">

            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-gray-900 dark:text-white text-sm">DormMS</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Management System</p>
                </div>
                <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-gray-400 hover:text-gray-600">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="home" class="w-4 h-4"></i>
                    Dashboard
                </a>

                <div class="pt-2 pb-1">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                </div>

                <a href="{{ route('admin.dormitories.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dormitories*') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="home" class="w-4 h-4"></i>
                    Dormitories
                </a>

                <a href="{{ route('admin.rooms.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.rooms*') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="grid" class="w-4 h-4"></i>
                    Rooms
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.students*') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="users" class="w-4 h-4"></i>
                    Students
                </a>

                <a href="{{ route('admin.allocations.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.allocations*') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="key" class="w-4 h-4"></i>
                    Allocations
                </a>

                <a href="{{ route('admin.payments.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.payments*') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="credit-card" class="w-4 h-4"></i>
                    Payments
                </a>

                @if(auth()->user()->isAdmin())
                <div class="pt-2 pb-1">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
                </div>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="shield" class="w-4 h-4"></i>
                    Users
                </a>
                @endif

                <div class="pt-2 pb-1">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</p>
                </div>

                <a href="{{ route('admin.notifications.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.notifications*') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="bell" class="w-4 h-4"></i>
                    Notifications
                    @php $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.profile') }}"
                   class="sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : 'text-gray-600 dark:text-gray-300' }}">
                    <i data-feather="user" class="w-4 h-4"></i>
                    Profile
                </a>
            </nav>

            <!-- User info -->
            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                         class="w-8 h-8 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                            <i data-feather="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-3 flex items-center gap-4 flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <i data-feather="menu" class="w-5 h-5"></i>
                </button>

                <div class="flex-1"></div>

                <!-- Dark mode toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <i data-feather="sun" class="w-4 h-4 dark:hidden"></i>
                    <i data-feather="moon" class="w-4 h-4 hidden dark:block"></i>
                </button>

                <!-- Notifications -->
                <div x-data="notificationsDropdown()" class="relative">
                    <button @click="toggle()" class="relative w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        <i data-feather="bell" class="w-4 h-4"></i>
                        <span x-show="count > 0" x-text="count"
                              class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center font-medium"></span>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                         class="absolute right-0 top-12 w-80 card z-50 overflow-hidden shadow-lg">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-sm font-semibold">Notifications</h3>
                            <a href="{{ route('admin.notifications.mark-all-read') }}"
                               onclick="event.preventDefault(); fetch(this.href, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(() => { open = false; location.reload(); })"
                               class="text-xs text-primary-600 hover:underline">Mark all read</a>
                        </div>
                        <div class="max-h-64 overflow-y-auto" id="notif-list">
                            <template x-for="n in notifications" :key="n.id">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer"
                                     :class="!n.is_read ? 'bg-primary-50 dark:bg-primary-900/20' : ''"
                                     @click="markRead(n.id)">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="n.title"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2" x-text="n.message"></p>
                                </div>
                            </template>
                            <div x-show="notifications.length === 0" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No new notifications
                            </div>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.notifications.index') }}" class="text-xs text-primary-600 hover:underline">View all notifications</a>
                        </div>
                    </div>
                </div>

                <!-- User menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                             class="w-7 h-7 rounded-full object-cover">
                        <span class="text-sm font-medium hidden sm:block">{{ auth()->user()->name }}</span>
                        <i data-feather="chevron-down" class="w-3 h-3 text-gray-400"></i>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                         class="absolute right-0 top-12 w-48 card shadow-lg z-50 overflow-hidden py-1">
                        <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <i data-feather="user" class="w-4 h-4"></i> Profile
                        </a>
                        <hr class="my-1 border-gray-200 dark:border-gray-700">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                <i data-feather="log-out" class="w-4 h-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash messages -->
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mx-4 sm:mx-6 mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-3">
                <i data-feather="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0"></i>
                <p class="text-sm text-green-800 dark:text-green-300">{{ session('success') }}</p>
                <button @click="show = false" class="ml-auto text-green-600"><i data-feather="x" class="w-4 h-4"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="mx-4 sm:mx-6 mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-3">
                <i data-feather="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0"></i>
                <p class="text-sm text-red-800 dark:text-red-300">{{ session('error') }}</p>
                <button @click="show = false" class="ml-auto text-red-600"><i data-feather="x" class="w-4 h-4"></i></button>
            </div>
            @endif

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        feather.replace();

        function notificationsDropdown() {
            return {
                open: false,
                count: 0,
                notifications: [],
                toggle() {
                    this.open = !this.open;
                    if (this.open) this.load();
                },
                async load() {
                    const res = await fetch('{{ route("admin.notifications.unread") }}');
                    const data = await res.json();
                    this.count = data.count;
                    this.notifications = data.notifications;
                },
                async markRead(id) {
                    await fetch(`/admin/notifications/${id}/read`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    this.load();
                },
                init() {
                    this.load();
                    setInterval(() => this.load(), 30000);
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>