<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Barca Academy Dormitory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#004D98] via-[#003060] to-[#7A003C] flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="bg-[#004D98] px-8 py-7 text-center">
            <div class="flex justify-center mb-3">
                <svg width="64" height="64" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="30" cy="30" rx="30" ry="30" fill="#EDBB00"/>
                    <ellipse cx="30" cy="30" rx="24" ry="24" fill="#004D98"/>
                    <rect x="10" y="10" width="40" height="40" rx="5" fill="#A50044"/>
                    <text x="30" y="38" text-anchor="middle" font-family="Georgia,serif"
                          font-weight="bold" font-size="22" fill="#EDBB00">ND</text>
                </svg>
            </div>
            <h1 class="text-white font-extrabold text-xl">Barca Academy Dormitory</h1>
            <p class="text-blue-300 text-xs mt-1">Room Allocation Management System</p>
        </div>

        <!-- Form -->
        <div class="px-8 py-8">
            <h2 class="text-gray-800 font-extrabold text-2xl mb-1">Welcome back 👋</h2>
            <p class="text-gray-400 text-sm mb-7">Sign in with your Admin or Student account</p>

            <!--
                CRITICAL CHECKLIST:
                ✅ method="POST"
                ✅ action="{{ route('login') }}"
                ✅ @csrf token present
                ✅ name="email" and name="password" match controller validation
            -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email Address
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="you@example.com"
                           class="w-full px-4 py-3 border rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#004D98]
                                  bg-gray-50 focus:bg-white transition
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           required
                           placeholder="••••••••"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#004D98]
                                  bg-gray-50 focus:bg-white transition">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="rounded border-gray-300 text-[#004D98] focus:ring-[#004D98]">
                    <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">
                        Keep me signed in
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full py-3.5 bg-[#A50044] text-white font-extrabold rounded-xl
                               hover:bg-[#7A003C] transition text-sm shadow-lg active:scale-[.98]">
                    Sign In
                </button>
            </form>

            <!-- Register link -->
            <p class="text-center text-sm text-gray-500 mt-6">
                No account?
                <a href="{{ route('register') }}" class="text-[#004D98] font-bold hover:text-[#A50044] transition">
                    Register as Student
                </a>
            </p>

            <!-- Demo credentials -->
            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <p class="text-xs font-bold text-blue-700 mb-2 uppercase tracking-wider">
                    🔑 Demo Login Credentials
                </p>
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-blue-600 font-semibold">Admin:</span>
                        <span class="text-blue-700 font-mono">admin@barca.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-600 font-semibold">Student:</span>
                        <span class="text-blue-700 font-mono">juan@student.com</span>
                    </div>
                    <div class="flex justify-between pt-1 border-t border-blue-100">
                        <span class="text-blue-500">Password (all):</span>
                        <span class="text-blue-700 font-mono font-bold">password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-center text-blue-300 text-xs mt-4">
        <a href="{{ route('home') }}" class="hover:text-white transition">← Back to Home</a>
    </p>
</div>

</body>
</html>