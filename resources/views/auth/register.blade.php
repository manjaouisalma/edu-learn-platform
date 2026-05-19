<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account – Edu-Learn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
        }

        /* ══ LEFT ══ */
        .left-panel {
            flex: 0 0 56%;
            background: linear-gradient(145deg, #1a3a8f 0%, #2251cc 48%, #3b6ef8 100%);
            padding: 52px 60px;
            display: flex; flex-direction: column; justify-content: center;
            position: relative; overflow: hidden;
        }
        .left-panel::before {
            content: ''; position: absolute;
            width: 460px; height: 460px; border-radius: 50%;
            background: rgba(255,255,255,.06);
            top: -130px; right: -110px;
        }
        .left-panel::after {
            content: ''; position: absolute;
            width: 320px; height: 320px; border-radius: 50%;
            background: rgba(255,255,255,.05);
            bottom: -90px; left: -90px;
        }

        .brand-row {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 72px; position: relative; z-index: 1;
        }
        .brand-icon {
            width: 48px; height: 48px;
            background: rgba(255,255,255,.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff;
        }
        .brand-name { font-size: 22px; font-weight: 700; color: #fff; }

        .hero-title {
            font-size: 44px; font-weight: 800; color: #fff;
            line-height: 1.15; margin-bottom: 20px;
            position: relative; z-index: 1;
        }
        .hero-desc {
            font-size: 15px; color: rgba(255,255,255,.72);
            line-height: 1.75; max-width: 440px;
            margin-bottom: 52px; position: relative; z-index: 1;
        }

        .features { display: flex; flex-direction: column; gap: 18px; position: relative; z-index: 1; }
        .feat {
            display: flex; align-items: center; gap: 14px;
            font-size: 14px; font-weight: 500; color: rgba(255,255,255,.9);
        }
        .feat-ico {
            width: 36px; height: 36px; flex-shrink: 0;
            background: rgba(255,255,255,.15);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff;
        }

        /* ══ RIGHT ══ */
        .right-panel {
            flex: 1;
            background: #f3f6ff;
            display: flex; align-items: center; justify-content: center;
            padding: 48px 44px;
            overflow-y: auto;
        }

        .form-card { width: 100%; max-width: 400px; }

        .form-brand {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 22px;
        }
        .form-brand-ico {
            width: 58px; height: 58px;
            background: linear-gradient(135deg, #4f7ef8, #2251cc);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: #fff;
            box-shadow: 0 6px 22px rgba(34,81,204,.35);
            margin-bottom: 10px;
        }
        .form-tagline { font-size: 13px; color: #8a96b0; }

        .form-title {
            text-align: center; font-size: 23px; font-weight: 800;
            color: #0f1c3f; margin-bottom: 22px;
        }

        .alert { border-radius: 10px; font-size: 13px; padding: 10px 14px; margin-bottom: 14px; }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

        .field { margin-bottom: 12px; }
        .inp-wrap { position: relative; display: flex; align-items: center; }
        .inp-wrap .ico {
            position: absolute; left: 16px;
            color: #b5bfce; font-size: 17px; pointer-events: none;
        }
        .inp-wrap input {
            width: 100%; padding: 13px 46px;
            border: 1.5px solid #dce3f3; border-radius: 13px;
            font-family: 'Inter', sans-serif; font-size: 14px;
            color: #0f1c3f; background: #fff; outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .inp-wrap input::placeholder { color: #b5bfce; }
        .inp-wrap input:focus {
            border-color: #2251cc;
            box-shadow: 0 0 0 3px rgba(34,81,204,.12);
        }
        .inp-wrap input.is-invalid { border-color: #f87171; }
        .toggle-pw {
            position: absolute; right: 14px;
            background: none; border: none; cursor: pointer;
            color: #b5bfce; font-size: 17px;
        }
        .toggle-pw:hover { color: #2251cc; }

        /* Role selector */
        .role-row { display: flex; gap: 12px; margin-bottom: 12px; }
        .role-lbl { flex: 1; cursor: pointer; }
        .role-lbl input[type="radio"] { display: none; }
        .role-box {
            border: 2px solid #dce3f3; border-radius: 13px;
            padding: 16px 10px; text-align: center;
            font-size: 13px; font-weight: 600; color: #8a96b0;
            background: #fff; transition: all .18s;
        }
        .role-box i { font-size: 24px; display: block; margin-bottom: 6px; }
        .role-lbl input[type="radio"]:checked + .role-box {
            border-color: #2251cc;
            background: #eef3ff; color: #2251cc;
            box-shadow: 0 0 0 3px rgba(34,81,204,.1);
        }

        .btn-submit {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #4f7ef8, #2251cc);
            border: none; border-radius: 13px;
            color: #fff; font-family: 'Inter', sans-serif;
            font-size: 15px; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 18px rgba(34,81,204,.35);
            transition: opacity .2s, transform .15s;
            margin-bottom: 18px;
        }
        .btn-submit:hover { opacity: .9; transform: translateY(-1px); }

        .form-footer { text-align: center; font-size: 13.5px; color: #8a96b0; }
        .form-footer a { color: #2251cc; font-weight: 700; text-decoration: none; }
        .form-footer a:hover { text-decoration: underline; }

        @media (max-width: 860px) {
            body { flex-direction: column; }
            .left-panel { flex: none; padding: 40px 28px; }
            .hero-title { font-size: 30px; }
            .right-panel { padding: 36px 20px; }
        }
    </style>
</head>
<body>

<!-- LEFT -->
<div class="left-panel">
    <div class="brand-row">
        <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <span class="brand-name">Edu-Learn</span>
    </div>

    <h1 class="hero-title">Learn without<br>limits.</h1>
    <p class="hero-desc">
        The modern e-learning platform connecting students with expert professors across every discipline.
    </p>

    <div class="features">
        <div class="feat"><div class="feat-ico"><i class="bi bi-check2"></i></div> Browse hundreds of courses</div>
        <div class="feat"><div class="feat-ico"><i class="bi bi-people-fill"></i></div> Learn from expert professors</div>
        <div class="feat"><div class="feat-ico"><i class="bi bi-graph-up"></i></div> Track your progress &amp; grades</div>
        <div class="feat"><div class="feat-ico"><i class="bi bi-shield-check"></i></div> Role-based secure access</div>
    </div>
</div>

<!-- RIGHT -->
<div class="right-panel">
    <div class="form-card">

        <div class="form-brand">
            <div class="form-brand-ico"><i class="bi bi-mortarboard-fill"></i></div>
            <span class="form-tagline">Online Learning Platform</span>
        </div>

        <h2 class="form-title">Create your account</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field">
                <div class="inp-wrap">
                    <i class="bi bi-person ico"></i>
                    <input type="text" name="name"
                        class="@error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Full name" required autofocus>
                </div>
            </div>

            <div class="field">
                <div class="inp-wrap">
                    <i class="bi bi-envelope ico"></i>
                    <input type="email" name="email"
                           class="@error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="Your email address" required>
                </div>
            </div>

            <div class="role-row">
                <label class="role-lbl">
                    <input type="radio" name="role" value="student"
                           {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                    <div class="role-box">
                        <i class="bi bi-person-fill"></i>
                        Student
                    </div>
                </label>
                <label class="role-lbl">
                    <input type="radio" name="role" value="professor"
                           {{ old('role') === 'professor' ? 'checked' : '' }}>
                    <div class="role-box">
                        <i class="bi bi-mortarboard-fill"></i>
                        Professor
                    </div>
                </label>
            </div>

            <div class="field">
                <div class="inp-wrap">
                    <i class="bi bi-lock ico"></i>
                    <input type="password" id="pw-register" name="password"
                           class="@error('password') is-invalid @enderror"
                           placeholder="Your password" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw-register',this)">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="field">
                <div class="inp-wrap">
                    <i class="bi bi-lock ico"></i>
                    <input type="password" id="pw-confirm" name="password_confirmation"
                           placeholder="Confirm password" required>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw-confirm',this)">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Create account</button>

            <p class="form-footer">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
            </p>
        </form>
    </div>
</div>

<script>
function togglePw(id, btn) {
    const inp = document.getElementById(id);
    const ico = btn.querySelector('i');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'text' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
</body>
</html>