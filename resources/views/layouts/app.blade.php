<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Modern Laravel application dashboard')">
    <title>@yield('title', 'Laravel App') — Nexus</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ─── Design Tokens ──────────────────────────────────────── */
        :root {
            --bg-base:       #0d0f1a;
            --bg-surface:    #131627;
            --bg-elevated:   #1a1f35;
            --bg-card:       #1e2340;

            --accent-1:      #6366f1;
            --accent-2:      #8b5cf6;
            --accent-3:      #06b6d4;
            --accent-grad:   linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%);
            --accent-soft:   rgba(99, 102, 241, 0.12);

            --text-primary:  #f1f5f9;
            --text-secondary:#94a3b8;
            --text-muted:    #64748b;

            --border:        rgba(255,255,255,0.07);
            --border-hover:  rgba(99,102,241,0.5);

            --sidebar-w:     260px;
            --topbar-h:      64px;

            --radius-sm:     8px;
            --radius-md:     14px;
            --radius-lg:     20px;
            --radius-xl:     28px;

            --shadow-card:   0 4px 24px rgba(0,0,0,0.4);
            --shadow-glow:   0 0 40px rgba(99,102,241,0.2);

            --transition:    0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ─── Reset & Base ───────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        a { color: inherit; text-decoration: none; }

        /* ─── Scrollbar ──────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--bg-elevated); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-1); }

        /* ─── App Shell ──────────────────────────────────────────── */
        .app-shell {
            display: grid;
            grid-template-rows: var(--topbar-h) 1fr;
            grid-template-columns: var(--sidebar-w) 1fr;
            grid-template-areas:
                "sidebar topbar"
                "sidebar content";
            min-height: 100vh;
        }

        /* ─── Topbar / Navbar ────────────────────────────────────── */
        .topbar {
            grid-area: topbar;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            background: rgba(19, 22, 39, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }

        .topbar-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 7px 16px;
            font-size: 0.85rem;
            color: var(--text-muted);
            transition: var(--transition);
            cursor: pointer;
        }
        .topbar-search:hover {
            border-color: var(--border-hover);
            color: var(--text-secondary);
        }
        .topbar-search svg { width: 14px; height: 14px; flex-shrink: 0; }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .topbar-icon-btn {
            width: 38px; height: 38px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--bg-elevated);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        .topbar-icon-btn:hover { border-color: var(--border-hover); color: var(--text-primary); background: var(--accent-soft); }
        .topbar-icon-btn svg { width: 17px; height: 17px; }
        .badge {
            position: absolute; top: -4px; right: -4px;
            width: 16px; height: 16px;
            border-radius: 999px;
            background: var(--accent-grad);
            font-size: 0.6rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--bg-surface);
        }

        .avatar-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 5px 14px 5px 5px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: var(--bg-elevated);
            cursor: pointer;
            transition: var(--transition);
        }
        .avatar-btn:hover { border-color: var(--border-hover); background: var(--accent-soft); }

        .avatar {
            width: 30px; height: 30px;
            border-radius: 999px;
            background: var(--accent-grad);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .avatar-info { display: flex; flex-direction: column; line-height: 1.2; }
        .avatar-name { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); }
        .avatar-role { font-size: 0.72rem; color: var(--text-muted); }

        /* ─── Sidebar ────────────────────────────────────────────── */
        .sidebar {
            grid-area: sidebar;
            width: var(--sidebar-w);
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 18px;
            border-bottom: 1px solid var(--border);
            height: var(--topbar-h);
        }

        .brand-logo {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: var(--accent-grad);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(99,102,241,0.4);
        }
        .brand-logo svg { width: 18px; height: 18px; color: #fff; }
        .brand-text { font-size: 1.1rem; font-weight: 800; letter-spacing: -0.03em; }
        .brand-text span { background: var(--accent-grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        .sidebar-body { flex: 1; padding: 16px 12px; display: flex; flex-direction: column; gap: 4px; }

        .nav-section-label {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 10px 10px 4px;
            margin-top: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }
        .nav-link svg { width: 17px; height: 17px; flex-shrink: 0; transition: var(--transition); }
        .nav-link .nav-label { flex: 1; }

        .nav-link:hover {
            background: var(--accent-soft);
            color: var(--text-primary);
        }
        .nav-link.active {
            background: var(--accent-soft);
            color: var(--accent-1);
            font-weight: 600;
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px;
            border-radius: 0 2px 2px 0;
            background: var(--accent-grad);
        }

        .nav-badge {
            font-size: 0.68rem; font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent-1);
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }
        .sidebar-footer-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.875rem; font-weight: 500;
            color: var(--text-secondary);
            transition: var(--transition);
            cursor: pointer;
        }
        .sidebar-footer-link:hover { background: rgba(239,68,68,0.1); color: #ef4444; }
        .sidebar-footer-link svg { width: 17px; height: 17px; }

        /* ─── Content Area ───────────────────────────────────────── */
        .content {
            grid-area: content;
            padding: 28px 32px;
            overflow-y: auto;
            background: var(--bg-base);
        }

        /* ─── Shared Component Styles ────────────────────────────── */
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 1.7rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text-primary); }
        .page-header p { margin-top: 4px; color: var(--text-secondary); font-size: 0.9rem; }

        .hero-banner {
            position: relative;
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 36px 40px;
            margin-bottom: 28px;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--accent-grad);
            opacity: 0.08;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 260px; height: 260px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-banner h1 {
            position: relative;
            font-size: 2rem; font-weight: 800;
            letter-spacing: -0.04em;
            background: var(--accent-grad);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            margin-bottom: 8px;
        }
        .hero-banner p {
            position: relative;
            color: var(--text-secondary);
            font-size: 0.95rem;
            max-width: 520px;
            line-height: 1.6;
        }

        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 22px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
        }
        .card-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-sm);
            background: var(--accent-soft);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
        }
        .card-icon svg { width: 20px; height: 20px; color: var(--accent-1); }
        .card-icon.cyan { background: rgba(6,182,212,0.12); }
        .card-icon.cyan svg { color: var(--accent-3); }
        .card-icon.purple { background: rgba(139,92,246,0.12); }
        .card-icon.purple svg { color: var(--accent-2); }
        .card-icon.green { background: rgba(16,185,129,0.12); }
        .card-icon.green svg { color: #10b981; }
        .card-icon.rose { background: rgba(244,63,94,0.12); }
        .card-icon.rose svg { color: #f43f5e; }

        .stat { font-size: 2rem; font-weight: 800; letter-spacing: -0.04em; color: var(--text-primary); margin-bottom: 4px; }
        .card h3 { font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
        .card-trend {
            font-size: 0.75rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 3px;
            padding: 2px 8px; border-radius: 999px;
        }
        .trend-up { background: rgba(16,185,129,0.12); color: #10b981; }
        .trend-down { background: rgba(244,63,94,0.12); color: #f43f5e; }

        .section-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }

        .list { display: flex; flex-direction: column; gap: 8px; }
        .list-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            transition: var(--transition);
        }
        .list-item:hover { border-color: var(--border-hover); }
        .list-dot { width: 8px; height: 8px; border-radius: 999px; flex-shrink: 0; }
        .list-text { flex: 1; color: var(--text-secondary); }
        .list-time { font-size: 0.75rem; color: var(--text-muted); }

        /* ─── Responsive ─────────────────────────────────────────── */
        @media (max-width: 900px) {
            .app-shell {
                grid-template-columns: 1fr;
                grid-template-rows: var(--topbar-h) 1fr;
                grid-template-areas: "topbar" "content";
            }
            .sidebar { display: none; }
            .content { padding: 20px 16px; }
            .topbar { padding: 0 16px; }
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="app-shell">
        @include('partials.sidebar')
        @include('partials.navbar')

        <main class="content">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
