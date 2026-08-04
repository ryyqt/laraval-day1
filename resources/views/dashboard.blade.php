@extends('layouts.app')

@section('title', 'Dashboard')
@section('meta_description', 'Nexus IMS — Dashboard overview: products, categories, customers, stock levels and recent activity.')

@section('styles')
<style>
/* ── Dashboard-specific styles ─────────────────────────── */

/* Page header */
.dash-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 14px; margin-bottom: 28px;
}
.dash-header h1 {
    font-size: 1.75rem; font-weight: 800; letter-spacing: -.035em;
    color: var(--text-primary);
}
.dash-header p { margin-top: 4px; color: var(--text-secondary); font-size: .9rem; }
.dash-date {
    font-size: .8rem; color: var(--text-muted);
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 999px;
    padding: 5px 14px;
    white-space: nowrap;
}

/* ── KPI stat cards ─────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.kpi-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    cursor: default;
}
.kpi-card:hover {
    border-color: var(--border-hover);
    box-shadow: var(--shadow-glow);
    transform: translateY(-2px);
}
.kpi-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
}
.kpi-card.indigo::before  { background: var(--accent-grad); }
.kpi-card.cyan::before    { background: linear-gradient(90deg,#06b6d4,#0ea5e9); }
.kpi-card.emerald::before { background: linear-gradient(90deg,#10b981,#34d399); }
.kpi-card.amber::before   { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
.kpi-card.rose::before    { background: linear-gradient(90deg,#f43f5e,#fb7185); }

.kpi-icon {
    width: 42px; height: 42px;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
}
.kpi-icon svg { width: 20px; height: 20px; }
.kpi-icon.indigo  { background: var(--accent-soft);            color: var(--accent-1); }
.kpi-icon.cyan    { background: rgba(6,182,212,.12);           color: #06b6d4; }
.kpi-icon.emerald { background: rgba(16,185,129,.12);          color: #10b981; }
.kpi-icon.amber   { background: rgba(245,158,11,.12);          color: #f59e0b; }
.kpi-icon.rose    { background: rgba(244,63,94,.12);           color: #f43f5e; }

.kpi-val {
    font-size: 1.9rem; font-weight: 800; letter-spacing: -.04em;
    color: var(--text-primary); margin-bottom: 4px;
    line-height: 1;
}
.kpi-label {
    font-size: .78rem; font-weight: 600; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .06em;
    margin-bottom: 10px;
}
.kpi-link {
    font-size: .8rem; font-weight: 600; text-decoration: none;
    display: inline-flex; align-items: center; gap: 4px;
    transition: var(--transition); opacity: .85;
}
.kpi-link:hover { opacity: 1; }
.kpi-link svg { width: 13px; height: 13px; transition: transform .2s; }
.kpi-link:hover svg { transform: translateX(3px); }

/* ── Two-column content row ──────────────────────────────── */
.content-row {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 18px;
    margin-bottom: 24px;
}
@media (max-width: 1100px) { .content-row { grid-template-columns: 1fr; } }

/* ── Panel card ──────────────────────────────────────────── */
.panel {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    flex-wrap: wrap; gap: 10px;
}
.panel-title {
    font-size: .95rem; font-weight: 700; color: var(--text-primary);
    display: flex; align-items: center; gap: 8px;
}
.panel-title svg { width: 17px; height: 17px; color: var(--accent-1); }
.panel-count { font-size: .78rem; color: var(--text-muted); }
.panel-link {
    font-size: .8rem; font-weight: 600; color: var(--accent-1);
    text-decoration: none; display: flex; align-items: center; gap: 4px;
    transition: var(--transition);
}
.panel-link svg { width: 13px; height: 13px; transition: transform .2s; }
.panel-link:hover svg { transform: translateX(3px); }

/* ── Table (shared) ──────────────────────────────────────── */
table { width: 100%; border-collapse: collapse; }
thead tr { background: var(--bg-elevated); }
thead th {
    padding: 10px 18px; text-align: left;
    font-size: .72rem; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border);
}
tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background var(--transition);
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: var(--bg-elevated); }
td {
    padding: 12px 18px; font-size: .875rem;
    color: var(--text-secondary); vertical-align: middle;
}
.prod-thumb {
    width: 42px; height: 42px; border-radius: 8px;
    object-fit: cover; border: 1px solid var(--border); flex-shrink: 0;
}
.prod-thumb-placeholder {
    width: 42px; height: 42px; border-radius: 8px;
    background: var(--bg-elevated); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.prod-thumb-placeholder svg { width: 18px; height: 18px; color: var(--text-muted); }
.prod-name-cell {
    display: flex; align-items: center; gap: 8px;
    color: var(--text-primary); font-weight: 600;
}
.prod-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--accent-grad); flex-shrink: 0;
}
.pill {
    display: inline-flex; padding: 3px 9px; border-radius: 999px;
    background: var(--accent-soft); color: var(--accent-1);
    font-size: .72rem; font-weight: 700;
}
.dt { font-size: .75rem; color: var(--text-muted); }

/* Panel footer */
.panel-footer {
    padding: 12px 20px; border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
}
.panel-footer a {
    font-size: .8rem; font-weight: 600; color: var(--accent-1);
    text-decoration: none; display: flex; align-items: center; gap: 4px;
    transition: var(--transition);
}
.panel-footer a svg { width: 13px; height: 13px; transition: transform .2s; }
.panel-footer a:hover svg { transform: translateX(3px); }

/* ── Quick-actions menu ──────────────────────────────────── */
.quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    padding: 16px;
}
.qa-btn {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 8px; padding: 18px 12px;
    background: var(--bg-elevated);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: var(--transition);
    text-align: center;
}
.qa-btn:hover {
    border-color: var(--border-hover);
    background: var(--accent-soft);
    transform: translateY(-2px);
    box-shadow: 0 4px 18px rgba(99,102,241,.15);
}
.qa-icon {
    width: 40px; height: 40px; border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
}
.qa-icon svg { width: 19px; height: 19px; }
.qa-icon.indigo  { background: var(--accent-soft);    color: var(--accent-1); }
.qa-icon.cyan    { background: rgba(6,182,212,.12);   color: #06b6d4; }
.qa-icon.emerald { background: rgba(16,185,129,.12);  color: #10b981; }
.qa-icon.amber   { background: rgba(245,158,11,.12);  color: #f59e0b; }
.qa-label { font-size: .78rem; font-weight: 600; color: var(--text-secondary); line-height: 1.3; }

/* ── Low-stock badge ─────────────────────────────────────── */
.stock-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 999px;
    font-size: .72rem; font-weight: 700;
}
.stock-critical { background: rgba(244,63,94,.12); color: #f43f5e; }
.stock-low      { background: rgba(245,158,11,.12); color: #f59e0b; }
.stock-ok       { background: rgba(16,185,129,.12); color: #10b981; }

/* ── Buttons ─────────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
    border-radius: var(--radius-sm); font-size: .875rem; font-weight: 600;
    cursor: pointer; transition: var(--transition); border: none;
    text-decoration: none; white-space: nowrap;
}
.btn svg { width: 16px; height: 16px; flex-shrink: 0; }
.btn-primary {
    background: var(--accent-grad); color: #fff;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
}
.btn-primary:hover { opacity: .9; transform: translateY(-1px); }

/* ── Empty state ─────────────────────────────────────────── */
.empty-state {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 48px 20px;
    gap: 12px; color: var(--text-muted); text-align: center;
}
.empty-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: var(--accent-soft);
    display: flex; align-items: center; justify-content: center;
}
.empty-icon svg { width: 26px; height: 26px; color: var(--accent-1); }
.empty-state h3 { font-size: 1rem; font-weight: 700; color: var(--text-secondary); }
.empty-state p  { font-size: .875rem; }

/* responsive */
@media (max-width: 900px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 540px) {
    .kpi-grid { grid-template-columns: 1fr; }
    .quick-actions { grid-template-columns: 1fr 1fr; }
}
</style>
@endsection

@section('content')

{{-- ── Page Header ── --}}
<div class="dash-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back! Here's your inventory overview at a glance.</p>
    </div>
    <span class="dash-date" id="dash-date-display"></span>
</div>

@include('partials.flash')

{{-- ── KPI Cards ── --}}
<div class="kpi-grid">

    {{-- Products --}}
    <div class="kpi-card indigo" id="kpi-products">
        <div class="kpi-icon indigo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.75 7.5h16.5M10.5 3.75h3A2.25 2.25 0 0 1 15.75 6v1.5h-7.5V6A2.25 2.25 0 0 1 10.5 3.75Z"/>
            </svg>
        </div>
        <div class="kpi-val" id="val-products">{{ number_format($totalProducts) }}</div>
        <div class="kpi-label">Total Products</div>
        <a href="{{ route('products.index') }}" class="kpi-link" style="color:var(--accent-1)">
            View all <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>

    {{-- Categories --}}
    <div class="kpi-card cyan" id="kpi-categories">
        <div class="kpi-icon cyan">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
            </svg>
        </div>
        <div class="kpi-val" id="val-categories">{{ number_format($totalCategories) }}</div>
        <div class="kpi-label">Categories</div>
        <a href="{{ route('categories.index') }}" class="kpi-link" style="color:#06b6d4">
            Manage <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>

    {{-- Customers --}}
    <div class="kpi-card emerald" id="kpi-customers">
        <div class="kpi-icon emerald">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
            </svg>
        </div>
        <div class="kpi-val" id="val-customers">{{ number_format($totalCustomers) }}</div>
        <div class="kpi-label">Customers</div>
        <a href="{{ route('customers.index') }}" class="kpi-link" style="color:#10b981">
            View all <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>

    {{-- Stock quantity --}}
    <div class="kpi-card amber" id="kpi-quantity">
        <div class="kpi-icon amber">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3"/>
            </svg>
        </div>
        <div class="kpi-val" id="val-quantity">{{ number_format($totalQuantity) }}</div>
        <div class="kpi-label">Total Stock Units</div>
        <span style="font-size:.78rem;color:var(--text-muted)">Across all products</span>
    </div>

    {{-- Inventory value --}}
    <div class="kpi-card rose" id="kpi-value">
        <div class="kpi-icon rose">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>
        <div class="kpi-val" id="val-value">${{ number_format($totalValue, 0) }}</div>
        <div class="kpi-label">Inventory Value</div>
        <span style="font-size:.78rem;color:var(--text-muted)">Total price × quantity</span>
    </div>

    {{-- Total Invoices --}}
    <div class="kpi-card indigo" id="kpi-invoices">
        <div class="kpi-icon indigo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
            </svg>
        </div>
        <div class="kpi-val" id="val-invoices">{{ number_format($totalInvoices) }}</div>
        <div class="kpi-label">Total Invoices</div>
        <a href="{{ route('invoices.index') }}" class="kpi-link" style="color:var(--accent-1)">
            View all <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>

    {{-- Revenue (Paid) --}}
    <div class="kpi-card emerald" id="kpi-revenue">
        <div class="kpi-icon emerald">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
            </svg>
        </div>
        <div class="kpi-val" id="val-revenue">${{ number_format($totalRevenue, 0) }}</div>
        <div class="kpi-label">Revenue (Paid)</div>
        <span style="font-size:.78rem;color:var(--text-muted)">From paid invoices</span>
    </div>

</div>

{{-- ── Two-column: Recent Products + Quick Actions ── --}}
<div class="content-row">

    {{-- Recent Products table --}}
    <div class="panel" id="panel-recent-products">
        <div class="panel-header">
            <span class="panel-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Recent Products
            </span>
            <span class="panel-count">Latest {{ $latestProducts->count() }} added</span>
        </div>

        @if($latestProducts->count())
            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestProducts as $product)
                        <tr>
                            <td style="padding:10px 18px">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="prod-thumb">
                                @else
                                    <div class="prod-thumb-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="prod-name-cell">
                                    <span class="prod-dot"></span>
                                    {{ $product->name }}
                                </div>
                            </td>
                            <td><span class="pill">{{ $product->category?->name ?? 'Uncategorized' }}</span></td>
                            <td>${{ number_format($product->price ?? 0, 2) }}</td>
                            <td>{{ number_format($product->quantity ?? 0) }}</td>
                            <td class="dt">{{ $product->created_at?->format('M d, Y') ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <a href="{{ route('products.index') }}">
                    View all products
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="{{ route('products.create') }}">
                    + Add product
                </a>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5"/>
                    </svg>
                </div>
                <h3>No products yet</h3>
                <p>Add your first product to start building the catalog.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-top:4px">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Product
                </a>
            </div>
        @endif
    </div>

    {{-- Right column: Quick Actions + Low Stock --}}
    <div style="display:flex;flex-direction:column;gap:18px">

        {{-- Quick Actions --}}
        <div class="panel" id="panel-quick-actions">
            <div class="panel-header">
                <span class="panel-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                    </svg>
                    Quick Actions
                </span>
            </div>
            <div class="quick-actions">
                <a href="{{ route('products.create') }}" class="qa-btn">
                    <div class="qa-icon indigo">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </div>
                    <span class="qa-label">Add Product</span>
                </a>
                <a href="{{ route('categories.create') }}" class="qa-btn">
                    <div class="qa-icon cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </div>
                    <span class="qa-label">Add Category</span>
                </a>
                <a href="{{ route('customers.create') }}" class="qa-btn">
                    <div class="qa-icon emerald">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                    </div>
                    <span class="qa-label">Add Customer</span>
                </a>
                <a href="{{ route('products.index') }}" class="qa-btn">
                    <div class="qa-icon amber">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>
                    </div>
                    <span class="qa-label">AJAX Products</span>
                </a>
            </div>
        </div>

        {{-- Low-Stock Alert --}}
        <div class="panel" id="panel-low-stock">
            <div class="panel-header">
                <span class="panel-title" style="color:var(--text-primary)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color:#f59e0b">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                    Low Stock
                </span>
                @if($lowStockCount > 0)
                    <span style="background:rgba(244,63,94,.12);color:#f43f5e;border:1px solid rgba(244,63,94,.25);border-radius:999px;padding:2px 10px;font-size:.72rem;font-weight:700">
                        {{ $lowStockCount }} item{{ $lowStockCount !== 1 ? 's' : '' }}
                    </span>
                @endif
            </div>

            @if($lowStockProducts->count())
                <div style="display:flex;flex-direction:column;gap:0">
                    @foreach($lowStockProducts as $ls)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:11px 18px;border-bottom:1px solid var(--border);font-size:.85rem;gap:8px">
                        <div style="font-weight:600;color:var(--text-primary);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1">
                            {{ $ls->name }}
                        </div>
                        <span class="stock-badge {{ $ls->quantity == 0 ? 'stock-critical' : 'stock-low' }}">
                            {{ $ls->quantity == 0 ? 'Out' : $ls->quantity . ' left' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <div class="panel-footer">
                    <a href="{{ route('products.index') }}">View all products <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg></a>
                </div>
            @else
                <div class="empty-state" style="padding:28px 20px">
                    <div class="empty-icon" style="background:rgba(16,185,129,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="color:#10b981">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>
                    <h3 style="font-size:.9rem">All stock levels OK</h3>
                    <p style="font-size:.8rem">No products are running low.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ── Animated count-up ───────────────────────────────────
function countUp(el, target, prefix, suffix, duration) {
    if (!el) return;
    duration = duration || 900;
    prefix   = prefix  || '';
    suffix   = suffix  || '';
    const start = performance.now();
    function step(now) {
        const progress = Math.min((now - start) / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        el.textContent = prefix + Math.floor(target * ease).toLocaleString() + suffix;
        if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}

document.addEventListener('DOMContentLoaded', function () {
    countUp(document.getElementById('val-products'),   {{ $totalProducts }});
    countUp(document.getElementById('val-categories'), {{ $totalCategories }});
    countUp(document.getElementById('val-customers'),  {{ $totalCustomers }});
    countUp(document.getElementById('val-quantity'),   {{ $totalQuantity }});
    countUp(document.getElementById('val-value'),      {{ (int)$totalValue }}, '$');
    countUp(document.getElementById('val-invoices'),   {{ $totalInvoices }});
    countUp(document.getElementById('val-revenue'),    {{ (int)$totalRevenue }}, '$');

    // Live date/time
    function updateClock() {
        const now = new Date();
        document.getElementById('dash-date-display').textContent =
            now.toLocaleDateString('en-US', { weekday:'short', year:'numeric', month:'short', day:'numeric' }) +
            '  ' + now.toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit' });
    }
    updateClock();
    setInterval(updateClock, 60000);
});
</script>
@endsection
