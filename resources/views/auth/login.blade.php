<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DormíPro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .barca-bg {
            background: linear-gradient(135deg, #0a1628 0%, #004d98 50%, #a50044 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15);
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.08);
            color: white;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.2s;
        }
        .form-input::placeholder { color: rgba(255,255,255,0.4); }
        .form-input:focus { border-color: #edbb00; box-shadow: 0 0 0 3px rgba(237,187,0,0.2); }
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            background: linear-gradient(135deg, #edbb00, #a50044);
            color: white;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(165,0,68,0.4); }
        .stripe-bar {
            height: 5px;
            background: repeating-linear-gradient(90deg, #a50044 0px, #a50044 33%, #004d98 33%, #004d98 66%, #edbb00 66%, #edbb00 100%);
            border-radius: 999px;
        }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .logo-badge { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="barca-bg flex items-center justify-center p-4">

    {{-- Background Orbs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-20 blur-3xl" style="background:#004d98"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full opacity-20 blur-3xl" style="background:#a50044"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 rounded-full opacity-10 blur-3xl -translate-x-1/2 -translate-y-1/2" style="background:#edbb00"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="logo-badge inline-flex items-center justify-center w-20 h-20 rounded-2xl shadow-2xl mb-4" style="background:linear-gradient(135deg,#edbb00,#a50044)">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">DormíPro</h1>
            <p class="text-white/50 text-sm mt-1">Room Allocation Management System</p>
            <div class="stripe-bar w-24 mx-auto mt-3"></div>
        </div>

        {{-- Card --}}
        <div class="glass-card rounded-3xl p-8 shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-1">Welcome back</h2>
            <p class="text-white/50 text-sm mb-6">Sign in to your account to continue</p>

            @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/20 border border-red-500/30 text-red-200 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-200 text-sm">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-white/60 text-xs font-semibold mb-1.5 uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                        class="form-input" placeholder="your@email.com">
                </div>

                <div>
                    <label class="block text-white/60 text-xs font-semibold mb-1.5 uppercase tracking-wider">Password</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        class="form-input" placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-white/60 text-sm cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/10 text-yellow-400 focus:ring-yellow-400">
                        Remember me
                    </label>
                    <a href="#" class="text-yellow-400 text-sm hover:text-yellow-300 transition-colors">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login mt-2">
                    Sign In to DormíPro
                </button>
            </form>
        </div>

        <p class="text-center text-white/30 text-xs mt-6">
            © {{ date('Y') }} DormíPro — Dormitory Management System. All rights reserved.
        </p>
    </div>
</body>
</html>