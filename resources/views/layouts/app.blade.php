<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Starter')</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
        }

        * { box-sizing: border-box; }
        body { margin: 0; background: #f3f6fb; color: #111827; }
        a { color: inherit; text-decoration: none; }

        .app-shell { min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }
        .brand { font-size: 1.1rem; font-weight: 700; color: #111827; }
        .topbar-actions { display: flex; gap: 12px; align-items: center; }
        .pill { padding: 8px 12px; border-radius: 999px; background: #eef2ff; color: #4338ca; font-size: 0.9rem; }

        .main-area { display: flex; flex: 1; }
        .sidebar {
            width: 260px;
            background: #111827;
            color: #f9fafb;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .sidebar h3 { margin: 0 0 8px; font-size: 1rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.08em; }
        .nav-link {
            display: block;
            padding: 10px 12px;
            border-radius: 10px;
            color: #d1d5db;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: #1f2937;
            color: #ffffff;
        }

        .content {
            flex: 1;
            padding: 24px;
        }
        .hero {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .hero h1 { margin: 0 0 8px; font-size: 1.7rem; }
        .hero p { margin: 0; opacity: 0.95; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }
        .card h3 { margin: 0 0 8px; font-size: 1rem; }
        .card p { margin: 0; color: #6b7280; }
        .stat { font-size: 1.8rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
        .muted { color: #6b7280; }
        .list { display: flex; flex-direction: column; gap: 10px; margin-top: 12px; }
        .list-item { padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; background: #f9fafb; }

        @media (max-width: 768px) {
            .main-area { flex-direction: column; }
            .sidebar { width: 100%; }
            .content { padding: 16px; }
            .topbar { padding: 12px 16px; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('partials.navbar')

        <div class="main-area">
            @include('partials.sidebar')

            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
