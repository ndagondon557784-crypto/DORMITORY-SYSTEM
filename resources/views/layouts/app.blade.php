<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dormitory Management') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --navy: #0f172a;
            --royal: #1e40af;
            --maroon: #7c2d12;
            --gold: #fbbf24;
            --light: #f8fafc;
            --dark: #1e293b;
        }

        body {
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a8a 100%);
            min-height: 100vh;
            color: var(--dark);
        }

        .glass-effect {
            background: rgba(248, 250, 252, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-gold {
            background: linear-gradient(135deg, var(--royal) 0%, var(--gold) 100%);
        }

        .shadow-lg-custom {
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.2);
        }

        .transition-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.3);
        }
    </style>
</head>
<body class="antialiased">
    @auth
    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="glass-effect w-64 hidden md:flex flex-col border-r border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 gradient-gold rounded-lg flex items-center justify-center text-white font-bold text-lg">
                        D
                    </div>
                    <div class="flex-1">
                        <h1 class="text-lg font-bold text-navy">Dorm</h1>
                        <p class="text-xs text-slate-600">Management</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                        Students
                    </x-nav-link>

                    <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H3a1.5 1.5 0 00-1.5 1.5v12a1.5 1.5 0 001.5 1.5h13a1.5 1.5 0 001.5-1.5V6.621a1.5 1.5 0 00-.44-1.06l-3.12-3.121A1.5 1.5 0 0013.38 1.5h-2.88z"/>
                        </svg>
                        Rooms
                    </x-nav-link>

                    <x-nav-link :href="route('allocations.index')" :active="request()->routeIs('allocations.*')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 000-2H7zM7 7a1 1 0 000 2h6a1 1 0 000-2H7zM7 11a1 1 0 100 2h6a1 1 0 100-2H7zM2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/>
                        </svg>
                        Allocations
                    </x-nav-link>

                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Payments
                    </x-nav-link>

                    <x-nav-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.5 3A1.5 1.5 0 001 4.5v.006c0 .649.413 1.199 1.5 2.742v3.752h3V7.25c1.087-1.543 1.5-2.093 1.5-2.742A1.5 1.5 0 0017.5 3h-15z"/>
                        </svg>
                        Announcements
                    </x-nav-link>
                @endif

                @if(Auth::user()->isStudent())
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        My Profile
                    </x-nav-link>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-slate-200">
                <div class="flex items-center gap-3 p-3 glass-effect rounded-lg">
                    <div class="w-10 h-10 bg-gradient-gold rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-navy truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-600 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-smooth">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="glass-effect border-b border-slate-200 sticky top-0 z-40">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-navy">{{ $pageTitle ?? 'Dashboard' }}</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="p-2 hover:bg-slate-100 rounded-lg transition-smooth">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>
                        <div class="relative">
                            <button onclick="document.getElementById('user-menu').classList.toggle('hidden')" class="flex items-center gap-2 p-2 hover:bg-slate-100 rounded-lg transition-smooth">
                                <div class="w-8 h-8 bg-gradient-gold rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 glass-effect rounded-lg shadow-lg py-2">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition-smooth">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-smooth">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-6">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h3 class="font-semibold text-red-800 mb-2">Validation Errors</h3>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenu = document.getElementById('user-menu');
            if (userMenu) {
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('[onclick*="user-menu"]')) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @else
        {{ $slot }}
    @endauth
</body>
</html>