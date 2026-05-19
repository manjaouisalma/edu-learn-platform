<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Edu-Learn') — Edu-Learn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:      #4f46e5;
            --primary-dark: #3730a3;
            --primary-light:#eef2ff;
            --accent:       #06b6d4;
            --success:      #10b981;
            --warning:      #f59e0b;
            --danger:       #ef4444;
            --sidebar-w:    260px;
            --sidebar-bg:   #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active:#ffffff;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            margin: 0;
            min-height: 100vh;
        }

        /* ── Sidebar ─────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 24px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            text-decoration: none;
        }

        .sidebar-brand .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }

        .sidebar-brand .logo-text {
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }

        .sidebar-user img {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,.2);
        }

        .sidebar-user .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }

        .sidebar-user .user-role {
            font-size: 11px;
            color: var(--sidebar-text);
            text-transform: capitalize;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
        }

        .sidebar-nav .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #475569;
            padding: 12px 24px 6px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 0;
            transition: all .15s ease;
            position: relative;
        }

        .sidebar-nav a:hover {
            color: #fff;
            background: rgba(255,255,255,.05);
        }

        .sidebar-nav a.active {
            color: #fff;
            background: rgba(79,70,229,.25);
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-nav a i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,.07);
        }

        .sidebar-footer form button {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--sidebar-text);
            background: none;
            border: none;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            padding: 8px 0;
            transition: color .15s;
            width: 100%;
        }

        .sidebar-footer form button:hover { color: #ef4444; }

        /* ── Main Content ─────────────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar .page-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .main-content {
            flex: 1;
            padding: 32px;
        }

        /* ── Cards ────────────────────────────────────────────── */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 12px 12px 0 0 !important;
            padding: 16px 20px;
            font-weight: 600;
        }

        /* ── Stat Cards ───────────────────────────────────────── */
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: box-shadow .2s;
        }

        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }

        .stat-icon {
            width: 56px; height: 56px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-icon.blue   { background: #eef2ff; color: var(--primary); }
        .stat-icon.teal   { background: #ecfdf5; color: var(--success); }
        .stat-icon.amber  { background: #fffbeb; color: var(--warning); }
        .stat-icon.cyan   { background: #ecfeff; color: var(--accent);  }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        /* ── Badges ───────────────────────────────────────────── */
        .badge-admin     { background: #fef3c7; color: #92400e; }
        .badge-professor { background: #dbeafe; color: #1e40af; }
        .badge-student   { background: #d1fae5; color: #065f46; }

        .badge {
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-info    { background: #dbeafe; color: #1e40af; }
        .badge-secondary { background: #f1f5f9; color: #475569; }

        /* ── Buttons ──────────────────────────────────────────── */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
        }

        /* ── Tables ───────────────────────────────────────────── */
        .table th {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td { vertical-align: middle; }

        /* ── Course Card ──────────────────────────────────────── */
        .course-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow .2s, transform .2s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .course-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,.1);
            transform: translateY(-2px);
        }

        .course-card-img {
            height: 140px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 48px;
            color: rgba(255,255,255,.6);
        }

        .course-card-body {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .course-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .course-card-desc {
            font-size: 13px;
            color: #64748b;
            flex: 1;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ── Alerts ───────────────────────────────────────────── */
        .alert { border-radius: 10px; border: none; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .alert-info    { background: #dbeafe; color: #1e40af; }

        /* ── Forms ────────────────────────────────────────────── */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e2e8f0;
            font-size: 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.15);
        }

        .form-label { font-size: 13px; font-weight: 600; color: #374151; }

        /* ── Grade Badge ─────────────────────────────────────── */
        .grade-circle {
            width: 48px; height: 48px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 800;
        }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">
    <a href="{{ url('/') }}" class="sidebar-brand">
    <div class="logo-icon" style="
        width: 36px;
        height: 36px;
        background: #2563eb;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    ">
        <i class="bi bi-mortarboard-fill" style="color:#fff;font-size:18px;"></i>
    </div>
    <div>
        <div style="font-size:16px;font-weight:800;color:#fff;letter-spacing:-0.5px;line-height:1.1;">
            Edu<span style="color:#60a5fa;">Learn</span>
        </div>
        <div style="font-size:10px;color:#475569;font-weight:500;letter-spacing:1px;text-transform:uppercase;">
            Platform
        </div>
    </div>
</a>

    <nav class="sidebar-nav">
        @yield('sidebar-nav')
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">
                <i class="bi bi-box-arrow-left"></i> Log Out
            </button>
        </form>
    </div>
</aside>

{{-- ── MAIN WRAPPER ── --}}
<div class="main-wrapper">
    <header class="topbar">
    <div class="page-title">@yield('page-title', 'Dashboard')</div>
    <div class="d-flex align-items-center gap-2">

        <span style="font-size:13px;font-weight:600;color:#1e293b;">
            {{ ucfirst(auth()->user()->role) }}
        </span>
        <div style="
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.5px;
        ">
           {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
    </div>
</header>

    <main class="main-content">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Please fix the following errors:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
