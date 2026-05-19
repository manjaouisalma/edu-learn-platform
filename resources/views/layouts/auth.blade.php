<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Welcome') — EduFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html,
body {
    height: 100%;
    overflow: hidden;
}

body {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    min-height: 100vh;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #edf1f7;
    background-image:
        radial-gradient(ellipse 80% 60% at 10% 0%, rgba(37, 99, 235, 0.08) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 90% 100%, rgba(99, 102, 241, 0.07) 0%, transparent 55%);
    background-attachment: fixed;
    overflow: hidden;
    -webkit-font-smoothing: antialiased;
}


.auth-wrapper {
    display: flex;
    width: 100%;
    max-width: 960px;
    height: calc(100vh - 48px);
    max-height: 820px;
    min-height: 0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow:
        0 2px 4px rgba(15, 23, 42, 0.04),
        0 8px 24px rgba(15, 23, 42, 0.08),
        0 24px 64px rgba(15, 23, 42, 0.1);
    margin: 0 20px;
    border: 1px solid rgba(255, 255, 255, 0.85);
    flex-shrink: 0;
}


.auth-hero {
    flex: 1;
    height: 100%;
    min-height: 0;
    background: linear-gradient(155deg, #0f2557 0%, #1a3a8f 35%, #1e63d0 70%, #2a7fe8 100%);
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 40px 44px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

.auth-hero::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -80px;
    width: 260px;
    height: 260px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.04);
    pointer-events: none;
}

.auth-hero::after {
    content: '';
    position: absolute;
    bottom: -100px;
    left: -60px;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.04);
    pointer-events: none;
}

.auth-hero .brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 36px;
    letter-spacing: -0.01em;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
}

.auth-hero .brand-icon {
    width: 38px;
    height: 38px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    border: 1px solid rgba(255, 255, 255, 0.18);
    flex-shrink: 0;
}

.auth-hero h1 {
    font-size: 27px;
    font-weight: 800;
    line-height: 1.22;
    margin-bottom: 12px;
    letter-spacing: -0.025em;
    color: #ffffff;
    position: relative;
    z-index: 1;
}

.auth-hero p {
    font-size: 13.5px;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.72);
    margin-bottom: 28px;
    position: relative;
    z-index: 1;
}

.auth-hero .features {
    display: flex;
    flex-direction: column;
    gap: 10px;
    position: relative;
    z-index: 1;
    list-style: none;
}

.auth-hero .features li {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.88);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
}

.auth-hero .features li i {
    font-size: 11px;
    background: rgba(255, 255, 255, 0.14);
    width: 26px;
    height: 26px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}



.auth-form-panel {
    width: 420px;
    min-width: 0;
    flex-shrink: 0;
    background: #ffffff;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 36px 42px;
    position: relative;
    border-left: 1px solid #e8edf4;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: #e2e8f0 transparent;
}

.auth-form-panel::-webkit-scrollbar {
    width: 4px;
}

.auth-form-panel::-webkit-scrollbar-track {
    background: transparent;
}

.auth-form-panel::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 4px;
}

.auth-form-panel::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #1d4ed8, #6366f1, #3b82f6);
    flex-shrink: 0;
}


.auth-form-panel h2 {
    font-size: 21px;
    font-weight: 800;
    color: #0d1b2e;
    margin-bottom: 5px;
    letter-spacing: -0.025em;
    line-height: 1.2;
    flex-shrink: 0;
}

.auth-form-panel .subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 24px;
    line-height: 1.6;
    font-weight: 400;
    flex-shrink: 0;
}


.form-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 5px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    display: block;
}

.form-control,
.form-select {
    width: 100%;
    border-radius: 9px;
    border: 1.5px solid #dde3ec;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    padding: 9px 13px;
    color: #0f172a;
    background: #f8fafd;
    transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    appearance: none;
    -webkit-appearance: none;
    line-height: 1.4;
}

.form-control::placeholder {
    color: #b0bcc8;
    font-weight: 400;
}

.form-control:hover,
.form-select:hover {
    border-color: #b8c4d8;
    background: #f5f8fc;
}

.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.11);
    background: #ffffff;
}


.input-icon-wrapper {
    position: relative;
}

.input-icon-wrapper i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
    pointer-events: none;
    transition: color 0.18s ease;
    z-index: 2;
}

.input-icon-wrapper .form-control {
    padding-left: 38px;
}

.input-icon-wrapper:focus-within i {
    color: #2563eb;
}

/* Reduce default Bootstrap margins */
.mb-3 {
    margin-bottom: 14px !important;
}

.mb-4 {
    margin-bottom: 18px !important;
}


.btn-auth {
    width: 100%;
    background: linear-gradient(135deg, #1a3fcc 0%, #2563eb 50%, #3b74f0 100%);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 11px 20px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    letter-spacing: 0.01em;
    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    position: relative;
    overflow: hidden;
}

.btn-auth:hover {
    transform: translateY(-1px);
    background: linear-gradient(135deg, #1535b8 0%, #1d4ed8 50%, #2563eb 100%);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
    color: #ffffff;
}

.btn-auth:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
}


.alert {
    border-radius: 9px;
    border: none;
    font-size: 12.5px;
    font-weight: 500;
    padding: 11px 14px;
    margin-bottom: 16px;
    line-height: 1.5;
}

.alert-danger {
    background: #fff5f5;
    color: #b91c1c;
    border-left: 3px solid #ef4444;
}

.alert-success {
    background: #f0fdf5;
    color: #15803d;
    border-left: 3px solid #22c55e;
}

a {
    color: #2563eb;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.15s ease;
}

a:hover {
    color: #1d4ed8;
    text-decoration: underline;
    text-underline-offset: 2px;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}

.form-check-input {
    width: 15px;
    height: 15px;
    border-radius: 4px;
    border: 1.5px solid #d1d5db;
    accent-color: #2563eb;
    cursor: pointer;
    flex-shrink: 0;
}

.form-check-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.form-check-label {
    font-size: 12.5px;
    color: #475569;
    font-weight: 500;
    cursor: pointer;
    line-height: 1.4;
}


@media (max-width: 768px) {
    html,
    body {
        overflow: hidden;
    }

    body {
        align-items: stretch;
        padding: 0;
    }

    .auth-wrapper {
        height: 100vh;
        max-height: 100vh;
        margin: 0;
        border-radius: 0;
        border: none;
        flex-direction: column;
    }

    .auth-hero {
        display: none;
    }

    .auth-form-panel {
        width: 100%;
        height: 100%;
        padding: 40px 28px;
        justify-content: center;
        overflow-y: auto;
    }
}

@media (max-width: 420px) {
    .auth-form-panel {
        padding: 32px 20px;
    }

    .auth-form-panel h2 {
        font-size: 19px;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .auth-wrapper {
        max-width: 820px;
        height: calc(100vh - 40px);
    }

    .auth-hero {
        padding: 36px 32px;
    }

    .auth-hero h1 {
        font-size: 24px;
    }

    .auth-form-panel {
        width: 380px;
        padding: 32px 34px;
    }
}
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-hero">
            <div class="brand">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                Edu-Learn
            </div>
            <h1>Learn without limits.</h1>
            <p>The modern e-learning platform connecting students with expert professors across every discipline.</p>
            <ul class="list-unstyled features">
                <li><i class="bi bi-check-lg"></i> Browse hundreds of courses</li>
                <li><i class="bi bi-people-fill"></i> Learn from expert professors</li>
                <li><i class="bi bi-graph-up"></i> Track your progress & grades</li>
                <li><i class="bi bi-shield-check"></i> Role-based secure access</li>
            </ul>
        </div>
        <div class="auth-form-panel">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 