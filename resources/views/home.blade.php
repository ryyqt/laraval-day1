@extends('layouts.app')

@section('title', 'Home')
@section('meta_description', 'Welcome to Nexus — your command center for projects, analytics, and team collaboration.')

@section('styles')
<style>
    /* ─── Home-specific styles ───────────────────────────────── */
    .welcome-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 18px;
        margin-bottom: 28px;
    }

    .quick-action-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 22px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
    }
    .quick-action-card:hover {
        border-color: var(--border-hover);
        box-shadow: var(--shadow-glow);
        transform: translateY(-3px);
    }
    .quick-action-card .qa-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .qa-icon.grad-1 { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .qa-icon.grad-2 { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .qa-icon.grad-3 { background: linear-gradient(135deg, #10b981, #059669); }
    .quick-action-card .qa-icon svg { width: 22px; height: 22px; color: #fff; }
    .quick-action-card h3 { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0; }
    .quick-action-card p { font-size: 0.83rem; color: var(--text-secondary); margin: 0; line-height: 1.5; }
    .quick-action-card .qa-arrow {
        margin-top: auto;
        font-size: 0.8rem;
        color: var(--accent-1);
        font-weight: 600;
        display: flex; align-items: center; gap: 4px;
    }
    .quick-action-card .qa-arrow svg { width: 14px; height: 14px; transition: var(--transition); }
    .quick-action-card:hover .qa-arrow svg { transform: translateX(4px); }

    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 28px; }

    .progress-item { display: flex; flex-direction: column; gap: 8px; padding: 10px 0; border-bottom: 1px solid var(--border); }
    .progress-item:last-child { border-bottom: none; }
    .progress-label { display: flex; justify-content: space-between; font-size: 0.85rem; }
    .progress-bar-bg { height: 6px; background: var(--bg-elevated); border-radius: 999px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 999px; background: var(--accent-grad); transition: width 1s ease; }

    .task-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
        color: var(--text-secondary);
    }
    .task-item:last-child { border-bottom: none; }
    .task-check {
        width: 18px; height: 18px;
        border-radius: 5px;
        border: 2px solid var(--border);
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: var(--transition);
    }
    .task-check.done { background: var(--accent-1); border-color: var(--accent-1); }
    .task-check.done svg { display: block; }
    .task-check svg { width: 10px; height: 10px; color: #fff; display: none; }
    .task-label { flex: 1; }
    .task-label.done-text { text-decoration: line-through; color: var(--text-muted); }

    @media (max-width: 1100px) { .welcome-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 700px)  { .welcome-grid, .two-col { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>Welcome back, Admin 👋</h1>
        <p>Here's what's happening across your workspace today.</p>
    </div>

    <!-- Hero Banner -->
    <div class="hero-banner" role="banner">
        <h1>Your Command Center</h1>
        <p>Track projects, monitor team progress, and manage everything from one beautifully unified dashboard.</p>
    </div>

    <!-- Stats Grid -->
    <div class="card-grid" style="margin-bottom:28px;">
        <article class="card">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/></svg>
            </div>
            <div class="stat" id="stat-projects">12</div>
            <h3>Active Projects</h3>
            <span class="card-trend trend-up">↑ 2 this week</span>
        </article>
        <article class="card">
            <div class="card-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
            </div>
            <div class="stat">48</div>
            <h3>Team Members</h3>
            <span class="card-trend trend-up">↑ 3 joined</span>
        </article>
        <article class="card">
            <div class="card-icon cyan">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat">84%</div>
            <h3>Sprint Progress</h3>
            <span class="card-trend trend-up">On track</span>
        </article>
        <article class="card">
            <div class="card-icon rose">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </div>
            <div class="stat">4</div>
            <h3>Pending Tasks</h3>
            <span class="card-trend trend-down">↓ Needs attention</span>
        </article>
    </div>

    <!-- Quick Actions -->
    <h2 class="section-title">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--accent-1)"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Quick Actions
    </h2>
    <div class="welcome-grid" style="margin-bottom:28px;">
        <a href="{{ route('dashboard') }}" class="quick-action-card" id="qa-dashboard">
            <div class="qa-icon grad-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Z"/></svg>
            </div>
            <h3>Open Dashboard</h3>
            <p>Get a full analytics overview of your workspace metrics.</p>
            <div class="qa-arrow">View Dashboard <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg></div>
        </a>
        <a href="#" class="quick-action-card" id="qa-reports">
            <div class="qa-icon grad-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75Z"/></svg>
            </div>
            <h3>View Reports</h3>
            <p>Explore detailed reports and exportable analytics data.</p>
            <div class="qa-arrow">See Reports <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg></div>
        </a>
        <a href="#" class="quick-action-card" id="qa-team">
            <div class="qa-icon grad-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
            </div>
            <h3>Manage Team</h3>
            <p>Add members, set roles, and manage team permissions.</p>
            <div class="qa-arrow">Manage <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg></div>
        </a>
    </div>

    <!-- Bottom Two Columns -->
    <div class="two-col">
        <!-- Sprint Progress -->
        <div class="card">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:17px;height:17px;color:var(--accent-1)"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75Z"/></svg>
                Sprint Progress
            </h2>
            <div class="progress-item">
                <div class="progress-label"><span style="color:var(--text-primary)">UI Design</span><span style="color:var(--accent-1);font-weight:600;">92%</span></div>
                <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:92%;background:linear-gradient(90deg,#6366f1,#8b5cf6);"></div></div>
            </div>
            <div class="progress-item">
                <div class="progress-label"><span style="color:var(--text-primary)">Backend API</span><span style="color:var(--accent-3);font-weight:600;">74%</span></div>
                <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:74%;background:linear-gradient(90deg,#06b6d4,#0284c7);"></div></div>
            </div>
            <div class="progress-item">
                <div class="progress-label"><span style="color:var(--text-primary)">Testing</span><span style="color:#10b981;font-weight:600;">48%</span></div>
                <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:48%;background:linear-gradient(90deg,#10b981,#059669);"></div></div>
            </div>
            <div class="progress-item">
                <div class="progress-label"><span style="color:var(--text-primary)">Deployment</span><span style="color:#f43f5e;font-weight:600;">20%</span></div>
                <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:20%;background:linear-gradient(90deg,#f43f5e,#be123c);"></div></div>
            </div>
        </div>

        <!-- Task Checklist -->
        <div class="card">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:17px;height:17px;color:var(--accent-1)"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                Today's Tasks
            </h2>
            <div class="task-item">
                <div class="task-check done"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></div>
                <span class="task-label done-text">Finalise homepage wireframes</span>
            </div>
            <div class="task-item">
                <div class="task-check done"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></div>
                <span class="task-label done-text">Set up Laravel routing</span>
            </div>
            <div class="task-item">
                <div class="task-check"></div>
                <span class="task-label">Review pull requests (#14, #15)</span>
            </div>
            <div class="task-item">
                <div class="task-check"></div>
                <span class="task-label">Update API documentation</span>
            </div>
            <div class="task-item">
                <div class="task-check"></div>
                <span class="task-label">Deploy staging build</span>
            </div>
        </div>
    </div>
@endsection
