<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DormMS — Dormitory Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        h1,h2,h3,.font-display { font-family: 'Syne', sans-serif; }
        .hero-bg {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            position: relative; overflow: hidden;
        }
        .hero-bg::before {
            content: ''; position: absolute; width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(99,102,241,0.3) 0%, transparent 70%);
            top: -200px; right: -200px; pointer-events: none;
        }
        .hero-bg::after {
            content: ''; position: absolute; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, transparent 70%);
            bottom: -100px; left: -100px; pointer-events: none;
        }
        .floating { animation: float 6s ease-in-out infinite; }
        .floating-delay { animation: float 6s ease-in-out infinite; animation-delay: 2s; }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .feature-card {
            background: white; border-radius: 20px; padding: 32px;
            border: 1px solid #f1f5f9;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        .glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .btn-hero {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 28px; border-radius: 14px;
            font-weight: 600; font-size: 16px;
            transition: all 0.3s; text-decoration: none;
        }
        .btn-hero-primary { background: #4f46e5; color: white; }
        .btn-hero-primary:hover { background: #3730a3; transform: translateY(-2px); box-shadow: 0 10px 30px rgba(79,70,229,0.4); }
        .btn-hero-outline { background: transparent; color: white; border: 2px solid rgba(255,255,255,0.3); }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.1); border-color: white; }
        .stat-pill {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px; border-radius: 999px;
            font-size: 14px; font-weight: 600;
        }
        .room-card { border-radius: 20px; overflow: hidden; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.3s; }
        .room-card:hover { transform: translateY(-4px); }
        .nav-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.3); }
        .nav-dot.active { background: #4f46e5; width: 24px; border-radius: 4px; }
        .grid-pattern {
            background-image: linear-gradient(rgba(99,102,241,0.1) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(99,102,241,0.1) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .fade-up { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="bg-white">

<!-- Navigation -->
<nav class="fixed top-0 left-0 right-0 z-50 py-4 px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="glass rounded-2xl px-5 py-3 flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i data-lucide="building-2" class="w-4 h-4 text-white"></i>
            </div>
            <span class="text-white font-display font-700 text-lg">DormMS</span>
        </div>
        <div class="glass rounded-2xl px-6 py-3 hidden md:flex items-center gap-6">
            <a href="#features" class="text-white/70 hover:text-white text-sm font-500 transition-colors">Features</a>
            <a href="#rooms" class="text-white/70 hover:text-white text-sm font-500 transition-colors">Rooms</a>
            <a href="#about" class="text-white/70 hover:text-white text-sm font-500 transition-colors">About</a>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="glass text-white text-sm font-500 px-5 py-2.5 rounded-xl hover:bg-white/20 transition-colors">Login</a>
            <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm font-600 px-5 py-2.5 rounded-xl hover:bg-indigo-500 transition-colors">Get Started</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-bg min-h-screen flex items-center pt-20">
    <div class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
        <div>
            <div class="inline-flex items-center gap-2 glass text-indigo-300 text-sm font-600 px-4 py-2 rounded-full mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Now Available — Laravel 11 Powered
            </div>
            <h1 class="font-display text-5xl lg:text-7xl font-800 text-white leading-tight mb-6">
                Smart Dorm
                <span class="text-transparent bg-clip-text" style="background: linear-gradient(135deg, #818cf8, #f59e0b);">Management</span>
                Made Easy
            </h1>
            <p class="text-white/60 text-lg leading-relaxed mb-10 max-w-lg">
                Streamline room allocations, track payments, manage students, and monitor occupancy — all in one powerful, beautiful platform.
            </p>
            <div class="flex flex-wrap gap-4 mb-12">
                <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    Start for Free
                </a>
                <a href="{{ route('login') }}" class="btn-hero btn-hero-outline">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    Login
                </a>
            </div>
            <!-- Stats -->
            <div class="flex flex-wrap gap-3">
                <div class="glass stat-pill text-white">
                    <i data-lucide="building" class="w-4 h-4 text-indigo-400"></i>
                    {{ $stats['total_rooms'] }} Rooms
                </div>
                <div class="glass stat-pill text-white">
                    <i data-lucide="users" class="w-4 h-4 text-green-400"></i>
                    {{ $stats['total_students'] }} Students
                </div>
                <div class="glass stat-pill text-white">
                    <i data-lucide="check-circle" class="w-4 h-4 text-amber-400"></i>
                    {{ $stats['available_rooms'] }} Available
                </div>
            </div>
        </div>
        <!-- Hero Visual -->
        <div class="relative hidden lg:block">
            <div class="floating glass rounded-3xl p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <div class="text-white/50 text-xs mb-1">Total Revenue</div>
                        <div class="text-white font-display font-700 text-3xl">₱284,500</div>
                    </div>
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-white/10 rounded-2xl p-4">
                        <div class="text-white/50 text-xs mb-1">Occupied</div>
                        <div class="text-white font-700 text-xl">78%</div>
                        <div class="mt-2 h-1.5 bg-white/20 rounded-full"><div class="h-full bg-indigo-400 rounded-full" style="width:78%"></div></div>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4">
                        <div class="text-white/50 text-xs mb-1">Students</div>
                        <div class="text-white font-700 text-xl">{{ $stats['total_students'] }}</div>
                        <div class="mt-2 h-1.5 bg-white/20 rounded-full"><div class="h-full bg-amber-400 rounded-full" style="width:65%"></div></div>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach(['Juan dela Cruz → Room 102', 'Maria Reyes → Room 201', 'Carlo Mendoza → Room 103'] as $item)
                    <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 bg-indigo-500 rounded-lg flex items-center justify-center">
                                <i data-lucide="user" class="w-3 h-3 text-white"></i>
                            </div>
                            <span class="text-white/80 text-sm">{{ $item }}</span>
                        </div>
                        <span class="text-xs bg-green-500/20 text-green-400 px-2 py-1 rounded-full">Active</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Floating Badges -->
            <div class="floating-delay glass absolute -top-4 -right-4 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <div class="text-white text-sm font-600">Payment Verified</div>
                    <div class="text-white/50 text-xs">₱3,500 received</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 80L60 69.3C120 58.7 240 37.3 360 32C480 26.7 600 37.3 720 42.7C840 48 960 48 1080 42.7C1200 37.3 1320 26.7 1380 21.3L1440 16V80H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- Features -->
<section id="features" class="py-24 px-6 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 fade-up">
            <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 text-sm font-600 px-4 py-2 rounded-full mb-4">
                <i data-lucide="zap" class="w-4 h-4"></i>
                Powerful Features
            </div>
            <h2 class="font-display text-5xl font-800 text-slate-900 mb-4">Everything You Need</h2>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">A complete suite of tools to manage your dormitory with efficiency and ease.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php $features = [
                ['icon' => 'calendar-check', 'color' => 'indigo', 'title' => 'Room Allocation', 'desc' => 'Auto and manual allocation with real-time availability tracking and conflict prevention.'],
                ['icon' => 'credit-card', 'color' => 'green', 'title' => 'Payment Management', 'desc' => 'Record, verify, and track payments with automated receipts and payment history.'],
                ['icon' => 'users', 'color' => 'blue', 'title' => 'Student Management', 'desc' => 'Complete student profiles with guardian info, medical notes, and status tracking.'],
                ['icon' => 'bar-chart-3', 'color' => 'purple', 'title' => 'Analytics & Reports', 'desc' => 'Visual dashboards with occupancy rates, revenue charts, and exportable reports.'],
                ['icon' => 'shield-check', 'color' => 'amber', 'title' => 'Role-Based Access', 'desc' => 'Admin, Staff, and Student roles with granular permission control.'],
                ['icon' => 'bell', 'color' => 'rose', 'title' => 'Smart Notifications', 'desc' => 'Real-time alerts for payments, allocations, and check-in/check-out events.'],
            ] @endphp
            @foreach($features as $i => $f)
            <div class="feature-card fade-up" style="transition-delay: {{ $i * 0.1 }}s">
                <div class="w-14 h-14 bg-{{ $f['color'] }}-100 rounded-2xl flex items-center justify-center mb-5">
                    <i data-lucide="{{ $f['icon'] }}" class="w-7 h-7 text-{{ $f['color'] }}-600"></i>
                </div>
                <h3 class="font-display text-xl font-700 text-slate-900 mb-3">{{ $f['title'] }}</h3>
                <p class="text-slate-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Rooms Showcase -->
@if($featuredRooms->count() > 0)
<section id="rooms" class="py-24 px-6 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-12 fade-up">
            <div>
                <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 text-sm font-600 px-4 py-2 rounded-full mb-4">
                    <i data-lucide="building" class="w-4 h-4"></i>
                    Available Rooms
                </div>
                <h2 class="font-display text-5xl font-800 text-slate-900">Find Your Room</h2>
            </div>
            <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl font-600 hover:bg-indigo-700 transition-colors">
                View All Rooms <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredRooms as $room)
            <div class="room-card fade-up">
                <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center relative overflow-hidden">
                    @if($room->photo)
                    <img src="{{ asset('storage/'.$room->photo) }}" alt="{{ $room->room_number }}" class="w-full h-full object-cover absolute inset-0">
                    @else
                    <i data-lucide="building-2" class="w-16 h-16 text-white/30"></i>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="bg-white text-slate-800 text-xs font-700 px-3 py-1.5 rounded-full uppercase">{{ $room->type }}</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-green-500 text-white text-xs font-700 px-3 py-1.5 rounded-full">Available</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-display text-xl font-700 text-slate-900">Room {{ $room->room_number }}</h3>
                            <p class="text-slate-500 text-sm">{{ $room->building }}, Floor {{ $room->floor }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-indigo-600 font-700 text-xl">₱{{ number_format($room->monthly_rate, 0) }}</div>
                            <div class="text-slate-400 text-xs">/month</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-500 mb-4">
                        <span class="flex items-center gap-1">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            {{ $room->capacity }} pax
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="venus-mars" class="w-3.5 h-3.5"></i>
                            {{ ucfirst($room->gender_type) }}
                        </span>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        @if($room->has_wifi) <span class="flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg"><i data-lucide="wifi" class="w-3 h-3"></i> WiFi</span> @endif
                        @if($room->has_aircon) <span class="flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg"><i data-lucide="wind" class="w-3 h-3"></i> A/C</span> @endif
                        @if($room->has_bathroom) <span class="flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg"><i data-lucide="bath" class="w-3 h-3"></i> Bath</span> @endif
                        @if($room->has_study_desk) <span class="flex items-center gap-1 text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg"><i data-lucide="book-open" class="w-3 h-3"></i> Desk</span> @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section class="py-24 px-6" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
    <div class="max-w-4xl mx-auto text-center fade-up">
        <h2 class="font-display text-5xl font-800 text-white mb-6">Ready to Get Started?</h2>
        <p class="text-white/70 text-xl mb-10">Join hundreds of students and administrators using DormMS to simplify dormitory management.</p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-indigo-600 font-700 text-lg px-8 py-4 rounded-2xl hover:bg-slate-100 transition-colors">
                <i data-lucide="rocket" class="w-5 h-5"></i>
                Create Account
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 border-2 border-white/30 text-white font-600 text-lg px-8 py-4 rounded-2xl hover:border-white hover:bg-white/10 transition-all">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Sign In
            </a>
        </div>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-8 text-white/60 text-sm">
            <span class="flex items-center gap-2"><i data-lucide="shield-check" class="w-4 h-4 text-green-400"></i> Secure & Reliable</span>
            <span class="flex items-center gap-2"><i data-lucide="zap" class="w-4 h-4 text-yellow-400"></i> Fast Performance</span>
            <span class="flex items-center gap-2"><i data-lucide="smartphone" class="w-4 h-4 text-blue-400"></i> Mobile Friendly</span>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-slate-900 py-12 px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center">
                <i data-lucide="building-2" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <div class="text-white font-display font-700">DormMS</div>
                <div class="text-slate-500 text-xs">Dormitory Management System</div>
            </div>
        </div>
        <div class="text-slate-500 text-sm">© {{ date('Y') }} DormMS. Built with Laravel 11 & Tailwind CSS.</div>
        <div class="flex gap-4">
            <a href="{{ route('login') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Login</a>
            <a href="{{ route('register') }}" class="text-slate-400 hover:text-white text-sm transition-colors">Register</a>
        </div>
    </div>
</footer>

<script>
    lucide.createIcons();
    // Scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>
</body>
</html>