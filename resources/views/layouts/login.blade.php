<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DormMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: { 50:'#eef2ff',600:'#4f46e5',700:'#4338ca' } } } }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #312e81 0%, #4f46e5 60%, #7c3aed 100%); }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        .float { animation: float 5s ease-in-out infinite; }
    </style>
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left panel -->
        <div class="hidden lg:flex flex-col justify-between w-1/2 gradient-bg p-12 relative overflow-hidden">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-10 right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-5 w-48 h-48 bg-purple-400/10 rounded-full blur-3xl"></div>
            </div>

            <a href="{{ route('home') }}" class="flex items-center gap-2 relative z-10">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xl">DormMS</span>
            </a>

            <div class="relative z-10 float">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/20 shadow-xl mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">A</span>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">Admin Dashboard</p>
                            <p class="text-white/60 text-xs">Room allocation overview</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white/10 rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-white">48</p>
                            <p class="text-white/60 text-xs">Students</p>
                        </div>
                        <div class="bg-white/10 rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-white">36</p>
                            <p class="text-white/60 text-xs">Active</p>
                        </div>
                        <div class="bg-white/10 rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-white">8</p>
                            <p class="text-white/60 text-xs">Available</p>
                        </div>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-white mb-4">Manage Your Dormitory Smarter</h1>
                <p class="text-white/70 text-lg leading-relaxed">
                    Complete room allocation, payment tracking, and student management — all in one powerful platform.
                </p>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-2">
                        @foreach(['A','B','C','D'] as $l)
                        <div class="w-8 h-8 bg-white/20 border-2 border-white/30 rounded-full flex items-center justify-center text-white text-xs font-medium">{{ $l }}</div>
                        @endforeach
                    </div>
                    <p class="text-white/70 text-sm">Trusted by dormitory administrators</p>
                </div>
            </div>
        </div>

        <!-- Right panel: Login form -->
        <div class="flex-1 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900">DormMS</span>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-1">Welcome back</h2>
                <p class="text-gray-500 text-sm mb-8">Sign in to your account to continue</p>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       placeholder="admin@dormms.ph"
                                       class="w-full pl-10 pr-4 py-2.5 border @error('email') border-red-400 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            </div>
                            <div x-data="{ show: false }" class="relative">
                                <input :type="show ? 'text' : 'password'" id="password" name="password"
                                       placeholder="••••••••"
                                       class="w-full pl-10 pr-10 py-2.5 border @error('password') border-red-400 @else border-gray-300 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" :disabled="loading"
                            class="mt-6 w-full py-2.5 bg-primary-600 hover:bg-primary-700 disabled:opacity-70 text-white font-semibold rounded-xl transition-all text-sm flex items-center justify-center gap-2 shadow-lg shadow-primary-600/30">
                        <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="loading ? 'Signing in...' : 'Sign In'">Sign In</span>
                    </button>
                </form>

                <!-- Demo credentials -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-xs font-semibold text-blue-700 mb-2">Demo Credentials</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-blue-600">
                        <div>
                            <p class="font-medium">Admin:</p>
                            <p>admin@dormms.ph</p>
                            <p>password</p>
                        </div>
                        <div>
                            <p class="font-medium">Staff:</p>
                            <p>staff@dormms.ph</p>
                            <p>password</p>
                        </div>
                    </div>
                </div>

                <p class="mt-6 text-center text-sm text-gray-500">
                    <a href="{{ route('home') }}" class="text-primary-600 hover:underline font-medium">← Back to Home</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>