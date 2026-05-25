<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — DormíPro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a1628 0%, #004d98 55%, #a50044 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Floating background orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            pointer-events: none;
        }
        .orb-1 { width: 500px; height: 500px; top: -150px; right: -150px; background: #004d98; }
        .orb-2 { width: 400px; height: 400px; bottom: -120px; left: -120px; background: #a50044; }
        .orb-3 { width: 300px; height: 300px; top: 40%; left: 40%; background: #edbb00; opacity: 0.08; }

        /* Glass card */
        .glass-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        }

        /* Logo badge */
        .logo-badge {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, #edbb00, #a50044);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 12px 35px rgba(165, 0, 68, 0.45);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }

        /* Stripe bar (Barca colors) */
        .stripe-bar {
            height: 4px;
            width: 56px;
            margin: 0.75rem auto 0;
            border-radius: 999px;
            background: repeating-linear-gradient(
                90deg,
                #a50044 0%, #a50044 33%,
                #004d98 33%, #004d98 66%,
                #edbb00 66%, #edbb00 100%
            );
        }

        /* Input fields */
        .field-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
            color: #ffffff;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field-input::placeholder { color: rgba(255, 255, 255, 0.38); }
        .field-input:focus {
            border-color: #edbb00;
            box-shadow: 0 0 0 3px rgba(237, 187, 0, 0.18);
        }
        .field-input.has-error {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.18);
        }

        /* Password wrapper */
        .pw-wrapper { position: relative; }
        .pw-toggle {
            position: absolute;
            inset-block: 0;
            right: 0.875rem;
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,0.4);
            transition: color 0.2s;
        }
        .pw-toggle:hover { color: rgba(255,255,255,0.8); }

        /* Login button */
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            cursor: pointer;
            background: linear-gradient(135deg, #edbb00 0%, #d97706 40%, #a50044 100%);
            color: #ffffff;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 6px 20px rgba(165, 0, 68, 0.35);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(165, 0, 68, 0.5);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert box */
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            border-radius: 12px;
            padding: 0.875rem 1rem;
            color: #fca5a5;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.35);
            border-radius: 12px;
            padding: 0.875rem 1rem;
            color: #6ee7b7;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        /* Labels */
        .field-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 0.4rem;
        }

        /* Remember / forgot row */
        .row-check {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.25rem;
        }
        .check-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.55);
            cursor: pointer;
            user-select: none;
        }
        .check-label input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #edbb00;
            cursor: pointer;
        }
        .forgot-link {
            font-size: 0.8rem;
            color: #edbb00;
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #fcd34d; }
    </style>
</head>
<body>

    {{-- Background orbs --}}
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div style="position:relative; z-index:10; width:100%; display:flex; flex-direction:column; align-items:center;">

        {{-- Logo --}}
        <div class="logo-badge">
            <svg width="36" height="36" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>

        <h1 style="font-size:1.875rem; font-weight:900; color:#ffffff; letter-spacing:-0.02em; text-align:center; margin-bottom:0.25rem;">
            DormíPro
        </h1>
        <p style="font-size:0.8rem; color:rgba(255,255,255,0.45); text-align:center; margin-bottom:0.5rem;">
            Room Allocation Management System
        </p>
        <div class="stripe-bar"></div>

        {{-- Card --}}
        <div class="glass-card" style="margin-top:2rem;" x-data="{ showPw: false, loading: false }">

            <h2 style="font-size:1.2rem; font-weight:700; color:#ffffff; margin-bottom:0.25rem;">
                Welcome back
            </h2>
            <p style="font-size:0.8rem; color:rgba(255,255,255,0.45); margin-bottom:1.5rem;">
                Sign in to your account to continue
            </p>

            {{-- Error message --}}
            @if($errors->any())
            <div class="alert-error">
                <strong>⚠ Login failed:</strong> {{ $errors->first() }}
            </div>
            @endif

            {{-- Success message (e.g. after logout) --}}
            @if(session('success'))
            <div class="alert-success">
                ✓ {{ session('success') }}
            </div>
            @endif

            {{-- Generic error from redirect --}}
            @if(session('error'))
            <div class="alert-error">
                ⚠ {{ session('error') }}
            </div>
            @endif

            <form
                action="{{ route('login.post') }}"
                method="POST"
                @submit="loading = true"
                style="display:flex; flex-direction:column; gap:1.25rem;"
            >
                @csrf

                {{-- Email --}}
                <div>
                    <label class="field-label" for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                        placeholder="your@email.com"
                        class="field-input {{ $errors->has('email') ? 'has-error' : '' }}"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label class="field-label" for="password">Password</label>
                    <div class="pw-wrapper">
                        <input
                            :type="showPw ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                            style="padding-right:2.75rem;"
                        >
                        <button type="button" class="pw-toggle" @click="showPw = !showPw" tabindex="-1">
                            {{-- Eye open --}}
                            <svg x-show="!showPw" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            {{-- Eye closed --}}
                            <svg x-show="showPw" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me + Forgot password --}}
                <div class="row-check">
                    <label class="check-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="btn-login"
                    :disabled="loading"
                    style="margin-top:0.25rem;"
                >
                    <span x-show="!loading">Sign In to DormíPro</span>
                    <span x-show="loading" style="display:flex; align-items:center; justify-content:center; gap:0.5rem;">
                        <svg style="animation:spin 1s linear infinite; width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        </svg>
                        Signing in…
                    </span>
                </button>
            </form>
        </div>

        <p style="margin-top:1.5rem; font-size:0.72rem; color:rgba(255,255,255,0.25); text-align:center;">
            © {{ date('Y') }} DormíPro — Dormitory Management System. All rights reserved.
        </p>
    </div>

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</body>
</html>