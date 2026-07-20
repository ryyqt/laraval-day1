@extends('layouts.app')

@section('title', 'Dashboard')
@section('meta_description', 'Analytics dashboard — visitors, revenue, orders, and recent activity at a glance.')

@section('styles')
<style>
    /* ─── Dashboard-specific styles ─────────────────────────── */
    .dash-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }

    .kpi-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 22px;
        transition: var(--transition);
        overflow: hidden;
        position: relative;
    }
    .kpi-card::after {
        content: '';
        position: absolute;
        bottom: -20px; right: -20px;
        width: 80px; height: 80px;
        border-radius: 999px;
        opacity: 0.06;
    }
    .kpi-card.c1::after { background: #6366f1; }
    .kpi-card.c2::after { background: #06b6d4; }
    .kpi-card.c3::after { background: #10b981; }
    .kpi-card.c4::after { background: #f59e0b; }

    .kpi-card:hover { border-color: var(--border-hover); transform: translateY(-2px); box-shadow: var(--shadow-glow); }

    .kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    .kpi-label { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; }
    .kpi-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .kpi-icon svg { width: 18px; height: 18px; }
    .kpi-icon.i1 { background: rgba(99,102,241,0.15); color: #6366f1; }
    .kpi-icon.i2 { background: rgba(6,182,212,0.15); color: #06b6d4; }
    .kpi-icon.i3 { background: rgba(16,185,129,0.15); color: #10b981; }
    .kpi-icon.i4 { background: rgba(245,158,11,0.15); color: #f59e0b; }

    .kpi-value { font-size: 2.1rem; font-weight: 800; letter-spacing: -0.04em; color: var(--text-primary); margin-bottom: 6px; }
    .kpi-delta { font-size: 0.78rem; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .kpi-delta.up { color: #10b981; }
    .kpi-delta.dn { color: #f43f5e; }

    /* ─── Chart placeholder ──────────────────────────────────── */
    .chart-wrap {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 24px;
        margin-bottom: 28px;
    }
    .chart-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .chart-header h2 { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
    .chart-tabs { display: flex; gap: 4px; }
    .chart-tab {
        padding: 5px 12px; border-radius: 6px;
        font-size: 0.8rem; font-weight: 600;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer; transition: var(--transition);
    }
    .chart-tab.active, .chart-tab:hover { background: var(--accent-soft); border-color: var(--border-hover); color: var(--accent-1); }

    /* Mini bar chart (pure CSS) */
    .mini-chart { display: flex; align-items: flex-end; gap: 6px; height: 120px; }
    .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .bar {
        width: 100%; border-radius: 4px 4px 0 0;
        background: var(--accent-soft);
        border: 1px solid rgba(99,102,241,0.2);
        transition: var(--transition);
        position: relative;
        cursor: pointer;
        min-height: 4px;
    }
    .bar:hover { background: var(--accent-1); }
    .bar-label { font-size: 0.7rem; color: var(--text-muted); white-space: nowrap; }

    /* ─── Bottom grid ────────────────────────────────────────── */
    .bottom-grid { display: grid; grid-template-columns: 1fr 380px; gap: 18px; }

    /* Activity feed */
    .activity-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid var(--border);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot-wrap { display: flex; flex-direction: column; align-items: center; gap: 0; padding-top: 4px; }
    .activity-dot { width: 10px; height: 10px; border-radius: 999px; flex-shrink: 0; }
    .activity-body { flex: 1; }
    .activity-text { font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5; }
    .activity-text strong { color: var(--text-primary); font-weight: 600; }
    .activity-time { font-size: 0.73rem; color: var(--text-muted); margin-top: 3px; }

    /* Top pages mini-table */
    .mini-table { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
    .mini-table th { padding: 6px 0; text-align: left; font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 1px solid var(--border); }
    .mini-table td { padding: 10px 0; border-bottom: 1px solid var(--border); color: var(--text-secondary); vertical-align: middle; }
    .mini-table tr:last-child td { border-bottom: none; }
    .mini-table .page-name { color: var(--text-primary); font-weight: 500; }
    .mini-table .visits-bar-bg { height: 5px; background: var(--bg-elevated); border-radius: 999px; margin-top: 4px; width: 100%; }
    .mini-table .visits-bar { height: 100%; border-radius: 999px; background: var(--accent-grad); }

    @media (max-width: 1200px) { .dash-kpi-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 900px)  { .dash-kpi-grid { grid-template-columns: 1fr; } .bottom-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Real-time overview of your application's key metrics and activity.</p>
    </div>

    <!-- Hero -->
    <div class="hero-banner" role="banner">
        <h1>Analytics Overview</h1>
        <p>Here is a comprehensive look at your visitors, revenue, orders, and everything in between — all updated in real time.</p>
    </div>

    <!-- KPI Grid -->
    <div class="dash-kpi-grid">
        <div class="kpi-card c1" id="kpi-visitors">
            <div class="kpi-top">
                <span class="kpi-label">Visitors</span>
                <div class="kpi-icon i1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                </div>
            </div>
            <div class="kpi-value">1,240</div>
            <div class="kpi-delta up">↑ 18.3% vs last month</div>
        </div>
        <div class="kpi-card c2" id="kpi-orders">
            <div class="kpi-top">
                <span class="kpi-label">Orders</span>
                <div class="kpi-icon i2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                </div>
            </div>
            <div class="kpi-value">89</div>
            <div class="kpi-delta up">↑ 7.1% vs last month</div>
        </div>
        <div class="kpi-card c3" id="kpi-revenue">
            <div class="kpi-top">
                <span class="kpi-label">Revenue</span>
                <div class="kpi-icon i3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
            </div>
            <div class="kpi-value">$12.4k</div>
            <div class="kpi-delta up">↑ 22.5% vs last month</div>
        </div>
        <div class="kpi-card c4" id="kpi-bounce">
            <div class="kpi-top">
                <span class="kpi-label">Bounce Rate</span>
                <div class="kpi-icon i4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/></svg>
                </div>
            </div>
            <div class="kpi-value">34.2%</div>
            <div class="kpi-delta dn">↓ 4.3% improvement</div>
        </div>
    </div>

    <!-- Traffic Chart -->
    <div class="chart-wrap">
        <div class="chart-header">
            <h2>Traffic Overview</h2>
            <div class="chart-tabs" role="tablist">
                <button class="chart-tab active" id="tab-7d" role="tab" aria-selected="true">7D</button>
                <button class="chart-tab" id="tab-30d" role="tab" aria-selected="false">30D</button>
                <button class="chart-tab" id="tab-90d" role="tab" aria-selected="false">90D</button>
            </div>
        </div>
        <div class="mini-chart" id="traffic-chart" role="img" aria-label="Weekly traffic bar chart">
            <div class="bar-col"><div class="bar" style="height:55px;" title="Mon – 820 visits"></div><span class="bar-label">Mon</span></div>
            <div class="bar-col"><div class="bar" style="height:80px;" title="Tue – 1,140 visits"></div><span class="bar-label">Tue</span></div>
            <div class="bar-col"><div class="bar" style="height:65px;" title="Wed – 960 visits"></div><span class="bar-label">Wed</span></div>
            <div class="bar-col"><div class="bar" style="height:100px;" title="Thu – 1,420 visits"></div><span class="bar-label">Thu</span></div>
            <div class="bar-col"><div class="bar" style="height:90px;" title="Fri – 1,280 visits"></div><span class="bar-label">Fri</span></div>
            <div class="bar-col"><div class="bar" style="height:45px;" title="Sat – 670 visits"></div><span class="bar-label">Sat</span></div>
            <div class="bar-col"><div class="bar" style="height:35px;" title="Sun – 520 visits"></div><span class="bar-label">Sun</span></div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="bottom-grid">
        <!-- Activity Feed -->
        <div class="card" id="activity-feed">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:17px;height:17px;color:var(--accent-1)"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                Recent Activity
            </h2>
            <div class="list">
                <div class="activity-item">
                    <div class="activity-dot-wrap"><div class="activity-dot" style="background:#10b981;"></div></div>
                    <div class="activity-body">
                        <div class="activity-text"><strong>New user signed up</strong> — Emily Clark joined the workspace.</div>
                        <div class="activity-time">2 minutes ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot-wrap"><div class="activity-dot" style="background:#6366f1;"></div></div>
                    <div class="activity-body">
                        <div class="activity-text"><strong>Invoice #102 marked paid</strong> — $1,400 cleared.</div>
                        <div class="activity-time">18 minutes ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot-wrap"><div class="activity-dot" style="background:#06b6d4;"></div></div>
                    <div class="activity-body">
                        <div class="activity-text"><strong>Report exported</strong> — Monthly analytics CSV generated.</div>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot-wrap"><div class="activity-dot" style="background:#f59e0b;"></div></div>
                    <div class="activity-body">
                        <div class="activity-text"><strong>Deployment triggered</strong> — Staging build #34 started.</div>
                        <div class="activity-time">3 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-dot-wrap"><div class="activity-dot" style="background:#f43f5e;"></div></div>
                    <div class="activity-body">
                        <div class="activity-text"><strong>Alert resolved</strong> — High memory usage on server-02 normalised.</div>
                        <div class="activity-time">Yesterday, 11:40 PM</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Pages -->
        <div class="card" id="top-pages">
            <h2 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:17px;height:17px;color:var(--accent-1)"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75Z"/></svg>
                Top Pages
            </h2>
            <table class="mini-table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th style="text-align:right;">Visits</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class="page-name">/home</div><div class="visits-bar-bg"><div class="visits-bar" style="width:95%;"></div></div></td>
                        <td style="text-align:right;font-weight:700;color:var(--text-primary);">1,240</td>
                    </tr>
                    <tr>
                        <td><div class="page-name">/dashboard</div><div class="visits-bar-bg"><div class="visits-bar" style="width:70%;"></div></div></td>
                        <td style="text-align:right;font-weight:700;color:var(--text-primary);">867</td>
                    </tr>
                    <tr>
                        <td><div class="page-name">/reports</div><div class="visits-bar-bg"><div class="visits-bar" style="width:52%;"></div></div></td>
                        <td style="text-align:right;font-weight:700;color:var(--text-primary);">644</td>
                    </tr>
                    <tr>
                        <td><div class="page-name">/users</div><div class="visits-bar-bg"><div class="visits-bar" style="width:38%;"></div></div></td>
                        <td style="text-align:right;font-weight:700;color:var(--text-primary);">470</td>
                    </tr>
                    <tr>
                        <td><div class="page-name">/settings</div><div class="visits-bar-bg"><div class="visits-bar" style="width:20%;"></div></div></td>
                        <td style="text-align:right;font-weight:700;color:var(--text-primary);">248</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Tab switcher for chart
    document.querySelectorAll('.chart-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.chart-tab').forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
        });
    });
</script>
@endsection
