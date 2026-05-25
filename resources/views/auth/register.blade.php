<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Create Account — DormSystem ND</title>
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
            max-width: 460px;
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
        .form-control.is-invalid { border-color: #ef4444 !important; }
        .field-error {
            font-size: 11px;
            color: #f87171;
            margin-top: 4px;
            display: block;
        }
        .input-wrap { position: relative; }
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
        .strength-bar {
            height: 3px;
            background: var(--border);
            border-radius: 999px;
            overflow: hidden;
            margin-top: 6px;
        }
        .strength-fill {
            height: 100%;
            border-radius: 999px;
            transition: width .3s, background .3s;
            width: 0;
        }
        .strength-text {
            font-size: 10px;
            color: #475569;
            margin-top: 3px;
            display: block;
        }
        .req-list {
            list-style: none;
            padding: 6px 0 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .req-list li {
            font-size: 11px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color .2s;
        }
        .req-list li.met { color: #34d399; }
        .match-msg {
            font-size: 11px;
            margin-top: 4px;
            display: none;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <div class="text-center mb-4">
        <div class="brand-logo">
            <i class="fa-solid fa-building fa-xl" style="color:#fff;"></i>
        </div>
        <h1 style="font-size:22px;font-weight:700;color:#fff;margin-bottom:4px;">DormSystem ND</h1>
        <p style="color:#64748b;font-size:13px;margin:0;">Create your new account</p>
    </div>

    <div class="auth-card">
        <h2 style="font-size:16px;font-weight:600;color:#fff;margin-bottom:24px;">Register New Account</h2>

        @if($errors->any())
            <div class="alert-err">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                <strong>Please fix the following:</strong>
                <ul style="margin:6px 0 0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li style="font-size:12px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" novalidate>
            @csrf

            {{-- Full Name --}}
            <div style="margin-bottom:16px;">
                <label class="form-label">Full Name</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user ico"></i>
                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Juan dela Cruz"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                    />
                </div>
                @error('name')
                    <span class="field-error"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div style="margin-bottom:16px;">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope ico"></i>
                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="you@ndmu.edu.ph"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    />
                </div>
                @error('email')
                    <span class="field-error"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div style="margin-bottom:16px;">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock ico"></i>
                    <input
                        type="password"
                        name="password"
                        id="reg-pass"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Min. 8 characters"
                        required
                        autocomplete="new-password"
                        oninput="checkStrength(this.value)"
                    />
                    <button type="button" class="eye-btn" onclick="togglePass('reg-pass', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="field-error"><i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $message }}</span>
                @enderror
                <div class="strength-bar"><div class="strength-fill" id="s-fill"></div></div>
                <span class="strength-text" id="s-text">Enter a password</span>
                <ul class="req-list" id="req-list">
                    <li id="r-len"><i class="fa-solid fa-circle-dot" style="font-size:9px;"></i> At least 8 characters</li>
                    <li id="r-let"><i class="fa-solid fa-circle-dot" style="font-size:9px;"></i> Contains a letter</li>
                    <li id="r-num"><i class="fa-solid fa-circle-dot" style="font-size:9px;"></i> Contains a number</li>
                </ul>
            </div>

            {{-- Confirm Password --}}
            <div style="margin-bottom:24px;">
                <label class="form-label">Confirm Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-shield-halved ico"></i>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="reg-confirm"
                        class="form-control"
                        placeholder="Re-enter your password"
                        required
                        autocomplete="new-password"
                        oninput="checkMatch()"
                    />
                    <button type="button" class="eye-btn" onclick="togglePass('reg-confirm', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="match-msg" id="match-msg"></div>
            </div>

            <button type="submit" class="btn-auth">
                <i class="fa-solid fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="divider">or</div>

        <div class="text-center">
            <span style="color:#64748b;font-size:13px;">Already have an account?</span>
            <a href="{{ route('login') }}" class="auth-link fw-semibold ms-1">Sign In</a>
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

function setReq(id, met) {
    const li   = document.getElementById(id);
    const icon = li.querySelector('i');
    if (met) {
        li.classList.add('met');
        icon.className = 'fa-solid fa-circle-check';
        icon.style.fontSize = '9px';
    } else {
        li.classList.remove('met');
        icon.className = 'fa-solid fa-circle-dot';
        icon.style.fontSize = '9px';
    }
}

function checkStrength(val) {
    const hasLen = val.length >= 8;
    const hasLet = /[a-zA-Z]/.test(val);
    const hasNum = /[0-9]/.test(val);

    setReq('r-len', hasLen);
    setReq('r-let', hasLet);
    setReq('r-num', hasNum);

    const score = [hasLen, hasLet, hasNum].filter(Boolean).length;
    const fill  = document.getElementById('s-fill');
    const text  = document.getElementById('s-text');

    if (val.length === 0) {
        fill.style.width = '0';
        fill.style.background = 'transparent';
        text.textContent = 'Enter a password';
        text.style.color = '#475569';
        return;
    }

    const levels = [
        { pct: '25%',  bg: '#ef4444', label: 'Too weak',  color: '#f87171' },
        { pct: '50%',  bg: '#f59e0b', label: 'Weak',      color: '#fbbf24' },
        { pct: '75%',  bg: '#f59e0b', label: 'Fair',      color: '#fbbf24' },
        { pct: '100%', bg: '#10b981', label: 'Strong ✓',  color: '#34d399' },
    ];

    const lv = levels[score - 1] || levels[0];
    fill.style.width      = lv.pct;
    fill.style.background = lv.bg;
    text.textContent      = lv.label;
    text.style.color      = lv.color;
}

function checkMatch() {
    const pass    = document.getElementById('reg-pass').value;
    const confirm = document.getElementById('reg-confirm').value;
    const msg     = document.getElementById('match-msg');

    if (confirm.length === 0) { msg.style.display = 'none'; return; }

    msg.style.display = 'block';
    if (pass === confirm) {
        msg.textContent = '✓ Passwords match';
        msg.style.color = '#34d399';
    } else {
        msg.textContent = '✗ Passwords do not match';
        msg.style.color = '#f87171';
    }
}
</script>
</body>
</html>