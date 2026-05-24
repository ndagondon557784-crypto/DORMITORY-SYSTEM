<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-navy via-royal to-maroon flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 gradient-gold rounded-2xl shadow-lg mb-4">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Dormitory</h1>
                <p class="text-gold/80">Management System</p>
            </div>

            <!-- Welcome Text -->
            <div class="text-center mb-8">
                <p class="text-white/80 text-sm">Welcome back! Please sign in to your account</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="glass-effect rounded-2xl p-8 space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-white mb-2">
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        @class([
                            'w-full px-4 py-3 rounded-lg bg-white/10 border-2 border-white/20 text-white placeholder-white/50 transition-smooth',
                            'focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30',
                            'border-red-500' => $errors->has('email'),
                        ])
                        placeholder="your@email.com"
                    />
                    @error('email')
                        <span class="text-xs text-red-300 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-white mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        @class([
                            'w-full px-4 py-3 rounded-lg bg-white/10 border-2 border-white/20 text-white placeholder-white/50 transition-smooth',
                            'focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30',
                            'border-red-500' => $errors->has('password'),
                        ])
                        placeholder="••••••••"
                    />
                    @error('password')
                        <span class="text-xs text-red-300 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 text-gold focus:ring-0">
                    <span class="text-sm text-white/80">Remember me</span>
                </label>

                <!-- Login Button -->
                <button type="submit" class="w-full py-3 rounded-lg gradient-gold text-white font-bold shadow-lg-custom hover-lift">
                    Sign In
                </button>

                <!-- Forgot Password -->
                <div class="text-center">
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-gold/80 hover:text-gold transition-smooth">
                            Forgot your password?
                        </a>
                    @endif
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-white/60 text-sm">
                <p>© {{ date('Y') }} Dormitory Management. All rights reserved.</p>
            </div>
        </div>
    </div>
</x-guest-layout>