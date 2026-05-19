<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Not Found | EduFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f4ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-box { text-align: center; max-width: 460px; padding: 32px; }
        .error-code {
            font-size: 96px;
            font-weight: 900;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-code">404</div>
        <h2 style="font-weight:800;color:#0f172a;margin-bottom:12px;">Page Not Found</h2>
        <p style="color:#64748b;margin-bottom:28px;">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">← Go Back</a>
        <a href="{{ url('/') }}" class="btn" style="background:#4f46e5;color:#fff;">Go to Dashboard</a>
    </div>
</body>
</html>
