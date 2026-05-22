<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barca Academy Dormitory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 antialiased">

<nav class="bg-[#004D98] text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-16">
        <div class="flex items-center gap-3">
            <svg width="36" height="36" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="30" cy="30" rx="30" ry="30" fill="#EDBB00"/>
                <ellipse cx="30" cy="30" rx="24" ry="24" fill="#004D98"/>
                <rect x="10" y="10" width="40" height="40" rx="5" fill="#A50044"/>
                <text x="30" y="38" text-anchor="middle" font-family="Georgia,serif" font-weight="bold" font-size="22" fill="#EDBB00">ND</text>
            </svg>
            <div>
                <p class="font-extrabold text-sm leading-tight">Barca Academy Dormitory</p>
                <p class="text-blue-300 text-[10px]">Room Allocation Management System</p>
            </div>
        </div>
        <div class="flex gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">Dashboard →</a>
            @else
                <a href="{{ route('login') }}"    class="px-4 py-2 border border-blue-300 text-white rounded-xl text-sm hover:bg-blue-700 transition">Sign In</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-[#A50044] text-white font-bold rounded-xl text-sm hover:bg-[#7A003C] transition">Register</a>
            @endauth
        </div>
    </div>
</nav>

<section class="bg-gradient-to-br from-[#004D98] via-[#00366b] to-[#7A003C] text-white py-28 px-4 text-center relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#EDBB00] opacity-5 rounded-full translate-x-32 -translate-y-32 pointer-events-none"></div>
    <div class="relative max-w-4xl mx-auto">
        <span class="inline-block bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-sm font-semibold mb-6">
            🏠 Dormitory Room Allocation System
        </span>
        <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
            Barca Academy<br><span class="text-[#EDBB00]">Dormitory</span>
        </h1>
        <p class="text-blue-200 text-lg mb-10 max-w-xl mx-auto">
            Your home away from home. Smart room management, seamless allocation, professional experience.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="px-10 py-4 bg-[#A50044] text-white font-bold rounded-2xl hover:bg-[#7A003C] transition text-lg shadow-2xl">Apply for a Room</a>
            <a href="{{ route('login') }}"    class="px-10 py-4 bg-white/10 text-white font-semibold rounded-2xl hover:bg-white/20 transition text-lg border border-white/30">Sign In</a>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto py-20 px-4">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-3">Everything You Need</h2>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['🏠','Room Management',   'Monitor all dorm rooms — availability, capacity, type, and pricing.'],
            ['📋','Smart Allocations', 'Auto capacity checks, conflict prevention, real-time room sync.'],
            ['👩‍🎓','Student Portal',   'Apply for rooms, track applications, view assigned room details.'],
            ['📊','Admin Dashboard',   'Full analytics, occupancy stats, pending applications at a glance.'],
            ['🔒','Secure Auth',       'Role-based access. Admin and student portals fully separated.'],
            ['📈','Reports & Logs',    'Occupancy reports, activity audit trail, demographic analytics.'],
        ] as [$icon,$title,$desc])
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all">
            <div class="text-4xl mb-3">{{ $icon }}</div>
            <h3 class="font-bold text-gray-800 text-xl mb-2">{{ $title }}</h3>
            <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

<section class="bg-[#004D98] text-white py-14 px-4">
    <div class="max-w-3xl mx-auto text-center">
        <h3 class="text-3xl font-extrabold mb-4">Ready to move in?</h3>
        <p class="text-blue-200 mb-8">Create your account and apply for a dormitory room today.</p>
        <a href="{{ route('register') }}"
           class="inline-block px-10 py-4 bg-[#EDBB00] text-[#004D98] font-extrabold rounded-2xl hover:bg-yellow-400 transition shadow-xl text-lg">
            Create Account →
        </a>
    </div>
</section>

<footer class="bg-[#004D98] border-t border-blue-800 text-blue-300 py-6 text-center text-sm">
    <p class="font-semibold text-white">Barca Academy Dormitory</p>
    <p class="text-xs mt-1">Room Allocation Management System &copy; {{ date('Y') }}</p>
</footer>

</body>
</html>