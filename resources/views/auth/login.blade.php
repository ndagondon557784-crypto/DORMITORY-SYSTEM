<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — DormSystem ND</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        :root{--barca-blue:#004D98;--barca-maroon:#A50044;--barca-gold:#EDBB00;--barca-dark:#0a1628;--barca-surface:#0f1f3d;--barca-card:#162447;--barca-border:#1e3560;}
        body{font-family:system-ui,sans-serif;background:var(--barca-dark);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px;}
        .input{width:100%;padding:10px 12px 10px 38px;background:var(--barca-surface);border:1px solid var(--barca-border);border-radius:8px;color:#fff;font-size:14px;outline:none;transition:border-color .2s;}
        .input:focus{border-color:var(--barca-blue);}
        .input::placeholder{color:#475569;}
    </style>
</head>
<body>
<div style="position:absolute;inset:0;overflow:hidden;pointer-events:none;">
    <div style="position:absolute;top:-160px;right:-160px;width:400px;height:400px;background:var(--barca-blue);border-radius:50%;opacity:.08;filter:blur(80px);"></div>
    <div style="position:absolute;bottom:-160px;left:-160px;width:400px;height:400px;background:var(--barca-maroon);border-radius:50%;opacity:.08;filter:blur(80px);"></div>
</div>

<div style="position:relative;width:100%;max-width:420px;">
    <div style="text-align:center;margin-bottom:32px;">
        <div style="display:inline-flex;width:64px;height:64px;background:linear-gradient(135deg,var(--barca-blue),var(--barca-maroon));border-radius:16px;align-items:center;justify-content:center;margin-bottom:16px;">
            <i class="fa-solid fa-building" style="color:#fff;font-size:28px;"></i>
        </div>
        <h1 style="font-size:24px;font-weight:700;color:#fff;">DormSystem ND</h1>
        <p style="color:#64748b;font-size:14px;margin-top:4px;">Dormitory Room Allocation System</p>
    </div>

    <div style="background:var(--barca-card);border:1px solid var(--barca-border);border-radius:16px;padding:32px;">
        <h2 style="font-size:16px;font-weight:600;color:#fff;margin-bottom:24px;">Sign in to your account</h2>

        @if($errors->any())
            <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#f87171;padding:10px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login" style="display:flex;flex-direction:column;gap:16px;">
            @csrf
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Email Address</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-envelope" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;font-size:14px;"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@ndmu.edu.ph" required class="input" style="padding-left:38px;" />
                </div>
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:500;color:#94a3b8;margin-bottom:6px;">Password</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-lock" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;font-size:14px;"></i>
                    <input type="password" name="password" placeholder="••••••••" required class="input" style="padding-left:38px;" id="pass-input"/>
                    <button type="button" onclick="togglePass()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#475569;cursor:pointer;font-size:14px;" id="pass-toggle">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="remember" id="remember" style="accent-color:var(--barca-blue);" />
                <label for="remember" style="font-size:13px;color:#94a3b8;cursor:pointer;">Remember me</label>
            </div>
            <button type="submit" style="width:100%;padding:11px;background:linear-gradient(135deg,var(--barca-blue),var(--barca-maroon));color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;margin-top:4px;">
                Sign In
            </button>
        </form>

        <div style="margin-top:20px;background:var(--barca-surface);border:1px solid var(--barca-border);border-radius:8px;padding:12px;">
            <p style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:4px;">Demo Credentials</p>
            <p style="font-size:11px;color:#475569;">Admin: admin@ndmu.edu.ph / admin123</p>
            <p style="font-size:11px;color:#475569;">Staff: staff@ndmu.edu.ph / staff123</p>
        </div>
    </div>
</div>
<script>
function togglePass(){
    const i=document.getElementById('pass-input');
    const t=document.getElementById('pass-toggle').querySelector('i');
    if(i.type==='password'){i.type='text';t.className='fa-solid fa-eye-slash';}
    else{i.type='password';t.className='fa-solid fa-eye';}
}
</script>
</body>
</html>