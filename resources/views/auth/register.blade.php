<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — DormMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        * { font-family: 'DM Sans', sans-serif; }
        h1,.font-display { font-family: 'Syne', sans-serif; }
        .auth-bg { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); }
        .form-input { width: 100%; padding: 11px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; outline: none; transition: all 0.2s; }
        .form-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
    </style>
</head>
<body class="min-h-screen auth-bg flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center">
                    <i data-lucide="building-2" class="w-6 h-6 text-white"></i>
                </div>
                <span class="font-display text-2xl font-700 text-white">DormMS</span>
            </a>
        </div>
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="font-display text-3xl font-700 text-slate-900 mb-2">Create Account</h1>
                <p class="text-slate-500">Join DormMS today</p>
            </div>
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                @foreach($errors->all() as $e)<div class="flex items-center gap-2"><i data-lucide="alert-circle" class="w-3 h-3"></i>{{ $e }}</div>@endforeach
            </div>
            @endif
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-600 text-slate-700 mb-2">Full Name</label>
                    <div class="relative">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input pl-11" placeholder="Juan dela Cruz" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-600 text-slate-700 mb-2">Email Address</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input pl-11" placeholder="you@example.com" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-600 text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        <input type="password" name="password" class="form-input pl-11" placeholder="Min. 8 characters" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-600 text-slate-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        <input type="password" name="password_confirmation" class="form-input pl-11" placeholder="Repeat password" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-600 py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Create Account
                </button>
            </form>
            <p class="text-center text-sm text-slate-500 mt-6">
                Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 font-600 hover:underline">Sign in</a>
            </p>
        </div>
        <p class="text-center mt-6">
            <a href="{{ route('landing') }}" class="text-white/40 hover:text-white/70 text-sm transition-colors flex items-center justify-center gap-2">
                <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to Home
            </a>
        </p>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>