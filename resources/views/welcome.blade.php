<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DormMS — Dormitory Room Allocation Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eef2ff',100:'#e0e7ff',500:'#6366f1',600:'#4f46e5',700:'#4338ca',900:'#312e81' }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gradient-hero { background: linear-gradient(135deg, #312e81 0%, #4f46e5 40%, #7c3aed 100%); }
        .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15); }
        .feature-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        .float { animation: float 6s ease-in-out infinite; }
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .nav-link { position: relative; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px; background:#6366f1; transition:width 0.3s; }
        .nav-link:hover::after { width:100%; }
        .stat-num { font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #6366f1, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-white text-gray-900">

    <!-- Navigation -->
    <nav x-data="{ open: false, scrolled: false }"
         @scroll.window="scrolled = window.scrollY > 50"
         :class="scrolled ? 'bg-white/95 backdrop-blur shadow-sm' : 'bg-transparent'"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span :class="scrolled ? 'text-gray-900' : 'text-white'" class="font-bold text-lg">DormMS</span>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#about" :class="scrolled ? 'text-gray-600 hover:text-primary-600' : 'text-white/80 hover:text-white'" class="text-sm font-medium nav-link transition-colors">About</a>
                    <a href="#features" :class="scrolled ? 'text-gray-600 hover:text-primary-600' : 'text-white/80 hover:text-white'" class="text-sm font-medium nav-link transition-colors">Features</a>
                    <a href="#stats" :class="scrolled ? 'text-gray-600 hover:text-primary-600' : 'text-white/80 hover:text-white'" class="text-sm font-medium nav-link transition-colors">Stats</a>
                    <a href="#contact" :class="scrolled ? 'text-gray-600 hover:text-primary-600' : 'text-white/80 hover:text-white'" class="text-sm font-medium nav-link transition-colors">Contact</a>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-medium px-4 py-2 rounded-lg transition-all"
                       :class="scrolled ? 'text-primary-600 hover:bg-primary-50' : 'text-white hover:bg-white/10'">Login</a>
                    <a href="{{ route('login') }}" class="text-sm font-medium px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30">
                        Get Started
                    </a>
                </div>

                <!-- Mobile menu button -->
                <button @click="open = !open" :class="scrolled ? 'text-gray-900' : 'text-white'" class="md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" x-cloak class="md:hidden bg-white rounded-xl shadow-xl mt-2 py-4 px-4 space-y-2">
                <a href="#about" @click="open = false" class="block py-2 text-gray-700 hover:text-primary-600 text-sm font-medium">About</a>
                <a href="#features" @click="open = false" class="block py-2 text-gray-700 hover:text-primary-600 text-sm font-medium">Features</a>
                <a href="#stats" @click="open = false" class="block py-2 text-gray-700 hover:text-primary-600 text-sm font-medium">Stats</a>
                <a href="#contact" @click="open = false" class="block py-2 text-gray-700 hover:text-primary-600 text-sm font-medium">Contact</a>
                <a href="{{ route('login') }}" class="block py-2 text-primary-600 font-medium text-sm">Login →</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-hero min-h-screen flex items-center relative overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 right-10 w-72 h-72 bg-white/5 rounded-full blur-3xl float"></div>
            <div class="absolute bottom-20 left-10 w-96 h-96 bg-purple-400/10 rounded-full blur-3xl float" style="animation-delay: 3s"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/3 rounded-full blur-3xl"></div>

            <!-- Grid pattern -->
            <svg class="absolute inset-0 w-full h-full opacity-5" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="fade-in-up">
                    <div class="glass inline-flex items-center gap-2 px-4 py-2 rounded-full text-white/90 text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        Now with AI-powered analytics
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Smart Dormitory
                        <span class="block text-transparent bg-clip-text" style="background: linear-gradient(90deg, #a5b4fc, #e879f9);">
                            Management
                        </span>
                        Made Simple
                    </h1>
                    <p class="text-lg text-white/75 mb-8 leading-relaxed">
                        A comprehensive platform for managing dormitory rooms, student allocations,
                        payments, and more — all in one beautifully designed system.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-primary-700 font-semibold rounded-xl hover:bg-gray-50 transition-all shadow-xl shadow-black/20 hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Access Dashboard
                        </a>
                        <a href="#features"
                           class="inline-flex items-center gap-2 px-6 py-3 glass text-white font-semibold rounded-xl hover:bg-white/15 transition-all">
                            Explore Features
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Mini stats -->
                    <div class="flex flex-wrap gap-6 mt-10">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white">100%</p>
                            <p class="text-sm text-white/60">Digital</p>
                        </div>
                        <div class="w-px bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white">Real-time</p>
                            <p class="text-sm text-white/60">Analytics</p>
                        </div>
                        <div class="w-px bg-white/20"></div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-white">24/7</p>
                            <p class="text-sm text-white/60">Access</p>
                        </div>
                    </div>
                </div>

                <!-- Hero illustration -->
                <div class="hidden lg:flex justify-center fade-in-up delay-300">
                    <div class="relative float">
                        <div class="glass rounded-2xl p-6 w-80 shadow-2xl">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-white font-semibold text-sm">Room Overview</span>
                                <span class="text-green-400 text-xs font-medium bg-green-400/20 px-2 py-1 rounded-full">Live</span>
                            </div>
                            <div class="space-y-3">
                                @foreach([['Room 101','Alice Cruz','Active'],['Room 102','Bob Santos','Active'],['Room 103','Available','—'],['Room 104','Maria Reyes','Active']] as $item)
                                <div class="flex items-center justify-between bg-white/10 rounded-lg px-3 py-2">
                                    <div>
                                        <p class="text-white text-xs font-medium">{{ $item[0] }}</p>
                                        <p class="text-white/60 text-xs">{{ $item[1] }}</p>
                                    </div>
                                    <span class="text-xs {{ $item[2] === 'Active' ? 'text-green-400 bg-green-400/20' : 'text-blue-300 bg-blue-400/20' }} px-2 py-0.5 rounded-full">{{ $item[2] }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t border-white/20">
                                <div class="flex justify-between text-xs text-white/60">
                                    <span>Occupancy</span>
                                    <span class="text-white font-medium">75%</span>
                                </div>
                                <div class="mt-2 h-2 bg-white/20 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating cards -->
                        <div class="absolute -top-4 -right-8 glass rounded-xl p-3 shadow-lg">
                            <p class="text-white text-xs font-medium">💰 Revenue</p>
                            <p class="text-white font-bold text-sm">₱48,500</p>
                        </div>
                        <div class="absolute -bottom-4 -left-8 glass rounded-xl p-3 shadow-lg">
                            <p class="text-white text-xs font-medium">👥 Students</p>
                            <p class="text-white font-bold text-sm">48 Active</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 80L48 74.7C96 69.3 192 58.7 288 53.3C384 48 480 48 576 53.3C672 58.7 768 69.3 864 69.3C960 69.3 1056 58.7 1152 53.3C1248 48 1344 48 1392 48L1440 48V80H1392C1344 80 1248 80 1152 80C1056 80 960 80 864 80C768 80 672 80 576 80C480 80 384 80 288 80C192 80 96 80 48 80H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">About the System</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2 mb-6">
                        Complete Dormitory Management at Your Fingertips
                    </h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        DormMS is a modern, comprehensive dormitory room allocation management system built with Laravel 11.
                        It streamlines the entire process of managing student housing — from room assignment to payment tracking.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        Designed for dormitory administrators and staff, DormMS provides real-time insights,
                        automated notifications, and an intuitive interface that makes managing hundreds of student
                        tenants effortless and efficient.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach(['Role-based Access','Real-time Dashboard','Payment Tracking','Activity Logs','Dark Mode','Mobile Responsive'] as $feature)
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <div class="w-5 h-5 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            {{ $feature }}
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-square bg-gradient-to-br from-primary-50 to-purple-50 rounded-3xl p-8 shadow-xl">
                        <div class="h-full bg-white rounded-2xl shadow-sm p-6 flex flex-col">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="flex-1 space-y-3">
                                <div class="h-8 bg-primary-600 rounded-lg flex items-center px-3">
                                    <span class="text-white text-xs font-medium">DormMS Dashboard</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="h-16 bg-green-50 border border-green-200 rounded-lg flex flex-col items-center justify-center">
                                        <span class="text-xl font-bold text-green-700">48</span>
                                        <span class="text-xs text-green-600">Students</span>
                                    </div>
                                    <div class="h-16 bg-blue-50 border border-blue-200 rounded-lg flex flex-col items-center justify-center">
                                        <span class="text-xl font-bold text-blue-700">24</span>
                                        <span class="text-xs text-blue-600">Rooms</span>
                                    </div>
                                </div>
                                <div class="h-24 bg-gray-50 border border-gray-200 rounded-lg p-3">
                                    <div class="flex justify-between items-end h-full gap-1">
                                        @foreach([40,65,55,80,70,90,75] as $h)
                                        <div class="flex-1 bg-primary-400 rounded-t" style="height: {{ $h }}%"></div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    @foreach(['Room 101 - Alice Cruz','Room 102 - Bob Santos','Room 103 - Available'] as $r)
                                    <div class="h-8 bg-gray-50 border border-gray-100 rounded flex items-center px-3">
                                        <span class="text-xs text-gray-600">{{ $r }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Features</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mt-2 mb-4">Everything You Need</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">A complete suite of tools to manage every aspect of your dormitory operations efficiently and professionally.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach([
                    ['🏠', 'Dormitory Management', 'Create and manage multiple dormitories with detailed information, capacity tracking, and status monitoring.', 'primary'],
                    ['🚪', 'Room Management', 'Manage rooms by type, capacity, floor, and amenities. Real-time availability checker with occupancy tracking.', 'blue'],
                    ['👥', 'Student Records', 'Comprehensive student profiles with academic info, emergency contacts, allocation history, and payment records.', 'green'],
                    ['🔑', 'Room Allocation', 'Seamlessly assign students to rooms with check-in/out date tracking and instant room availability updates.', 'yellow'],
                    ['💳', 'Payment Management', 'Track monthly rent, deposits, utilities, and penalties. Multiple payment methods with receipt generation.', 'purple'],
                    ['📊', 'Analytics Dashboard', 'Real-time charts, revenue reports, occupancy rates, and trend analysis at a glance.', 'red'],
                    ['🔔', 'Notifications', 'Instant alerts for new allocations, upcoming checkouts, overdue payments, and system events.', 'orange'],
                    ['🛡️', 'Role-based Access', 'Admin and Staff roles with different permission levels. Secure authentication and session management.', 'teal'],
                    ['📝', 'Activity Logs', 'Complete audit trail of all system actions with user, timestamp, and change details for accountability.', 'indigo'],
                ] as [$icon, $title, $desc, $color])
                <div class="feature-card bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 rounded-xl bg-{{ $color }}-100 flex items-center justify-center text-2xl mb-4">{{ $icon }}</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-24" style="background: linear-gradient(135deg, #312e81 0%, #4f46e5 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Built for Scale</h2>
                <p class="text-white/70 max-w-2xl mx-auto">DormMS is designed to handle dormitories of all sizes, from small boarding houses to large university dormitories.</p>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach([['∞','Rooms Supported'],['5+','Payment Methods'],['2','Access Roles'],['100%','Responsive']] as [$num,$label])
                <div class="text-center glass rounded-2xl p-8">
                    <div class="stat-num" style="font-size: 2.5rem; background: linear-gradient(135deg, #a5b4fc, #e879f9); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $num }}</div>
                    <p class="text-white/70 text-sm mt-2">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-start">
                <div>
                    <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">Contact Us</span>
                    <h2 class="text-3xl font-bold text-gray-900 mt-2 mb-6">Get in Touch</h2>
                    <p class="text-gray-600 mb-8">Have questions about DormMS? We're here to help you get started with the perfect dormitory management solution.</p>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Email</p>
                                <p class="text-sm text-gray-600">admin@dormms.ph</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Phone</p>
                                <p class="text-sm text-gray-600">+63 912 345 6789</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Address</p>
                                <p class="text-sm text-gray-600">Davao City, Philippines</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Send a Message</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" placeholder="Juan dela Cruz" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" placeholder="juan@email.com" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea rows="4" placeholder="Your message..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                        </div>
                        <button class="w-full py-2.5 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors text-sm">
                            Send Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="font-bold text-lg">DormMS</span>
                </div>
                <p class="text-gray-400 text-sm">© {{ date('Y') }} DormMS. Built with Laravel 11 & Tailwind CSS.</p>
                <div class="flex gap-6">
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Login</a>
                    <a href="#about" class="text-gray-400 hover:text-white text-sm transition-colors">About</a>
                    <a href="#contact" class="text-gray-400 hover:text-white text-sm transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>