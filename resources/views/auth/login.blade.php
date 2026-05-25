<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — DormSystem ND</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        :root {
            --primary: #004D98;
            --secondary: #A50044;
            --gold: #EDBB00;
            --dark: #0a1628;
            --surface: #0f1f3d;
            --card: #162447;
            --border: #1e3560;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: -200px; right: -200px;
            width: 500px; height: 500px;
            background: var(--primary);
            border-radius: 50%;
            opacity: .07;
            filter: blur(80px);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -200px; left: -200px;
            width: 500px; height: 500px;
            background: var(--secondary);
            border-radius: 50%;
            opacity: .07;
            filter: blur(80px);
            pointer-events: none;
        }
        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
        }
        .brand-logo {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 32px rgba(0,77,152,.3);
        }
        .auth-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
        }
        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 6px;
            display: block;
        }
        .form-control {
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            color: #fff !important;
            border-radius: 10px !important;
            padding: 10px 14px 10px 38px !important;
            font-size: 14px !important;
            transition: border-color .2s, box-shadow .2s !important;
            width: 100%;
        }
        .form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(0,77,152,.2) !important;
            outline: none !important;
        }
        .form-control::placeholder { color: #475569 !important; }
        .input-wrap {
            position: relative;
        }
        .input-wrap .ico {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            font-size: 13px;
            pointer-events: none;
        }
        .input-wrap .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #475569;
            cursor: pointer;
            font-size: 13px;
            padding: 0;
            line-height: 1;
            transition: color .2s;
        }
        .input-wrap .eye-btn:hover { color: #94a3b8; }
        .btn-auth {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-auth:hover { opacity: .9; transform: translateY(-1px); }
        .btn-auth:active { transform: translateY(0); }
        .form-check-input {
            background-color: var(--surface) !important;
            border-color: var(--border) !important;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .form-check-label {
            font-size: 13px;
            color: #94a3b8;
            cursor: pointer;
        }
        .auth-link {
            color: #60a5fa;
            text-decoration: none;
            font-size: 13px;
            transition: color .2s;
        }
        .auth-link:hover { color: #93c5fd; }
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #475569;
            font-size: 12px;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .alert-err {
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.25);
            color: #fca5a5;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .alert-ok {
            background: rgba(16,185,129,.1);
            border: 1px solid rgba(16,185,129,.25);
            color: #6ee7b7;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .demo-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 20px;
        }
        .demo-box p { margin: 0; font-size: 11px; color: #475569; }
        .demo-box p:first-child { color: #64748b; font-weight: 600; margin-bottom: 4px; }
        .forgot-link {
            font-size: 11px;
            color: #60a5fa;
            text-decoration: none;
            transition: color .2s;
        }
        .forgot-link:hover { color: #93c5fd; }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <div class="text-center mb-4">
        <div class="brand-logo">
            <i class="fa-solid fa-building fa-xl" style="color:#fff;"></i>
        </div>
        <h1 style="font-size:22px;font-weight:700;color:#fff;margin-bottom:4px;">DormSystem ND</h1>
        <p style="color:#64748b;font-size:13px;margin:0;">Dormitory Room Allocation System</p>
    </div>

    <div class="auth-card">
        <h2 style="font-size:16px;font-weight:600;color:#fff;margin-bottom:24px;">Sign in to your account</h2>

        @if($errors->any())
            <div class="alert-err">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert-ok">
                <i class="fa-solid fa-circle-check me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" novalidate>
            @csrf

            <div style="margin-bottom:16px;">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope ico"></i>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="you@ndmu.edu.ph"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    />
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <label class="form-label" style="margin:0;">Password</label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock ico"></i>
                    <input
                        type="password"
                        name="password"
                        id="login-pass"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    />
                    <button type="button" class="eye-btn" onclick="togglePass('login-pass', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn-auth">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In
            </button>
        </form>

        <div class="divider">or</div>

        <div class="text-center">
            <span style="color:#64748b;font-size:13px;">Don't have an account?</span>
            <a href="{{ route('register') }}" class="auth-link fw-semibold ms-1">Create Account</a>
        </div>

        <div class="demo-box">
            <p>Demo Credentials</p>
            <p>Admin: admin@ndmu.edu.ph / admin123</p>
            <p>Staff: staff@ndmu.edu.ph / staff123</p>
        </div>
    </div>

    <p class="text-center mt-3" style="color:#334155;font-size:11px;">
        © {{ date('Y') }} Notre Dame — DormSystem ND
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass(id, btn) {
    const inp  = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}
</script>
</body>
</html>