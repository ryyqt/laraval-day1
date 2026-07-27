@extends('layouts.app')

@section('title', 'Dashboard')
@section('meta_description', 'Dashboard — total products, categories, stock quantity, and latest products.')

@section('styles')
<style>
    /* ── Page header (matches product page) ── */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 28px; flex-wrap: wrap; gap: 14px;
    }
    .page-header h1 {
        font-size: 1.7rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text-primary);
    }
    .page-header p { margin-top: 4px; color: var(--text-secondary); font-size: 0.9rem; }

    /* ── Stat cards grid ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 22px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        border-color: var(--border-hover);
        box-shadow: var(--shadow-glow);
        transform: translateY(-2px);
    }

    .stat-card-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 14px;
    }
    .stat-card-icon svg { width: 20px; height: 20px; }
    .stat-card-icon.indigo  { background: var(--accent-soft); color: var(--accent-1); }
    .stat-card-icon.cyan    { background: rgba(6,182,212,0.12); color: var(--accent-3); }
    .stat-card-icon.emerald { background: rgba(16,185,129,0.12); color: #10b981; }

    .stat-card .stat-value {
        font-size: 2rem; font-weight: 800; letter-spacing: -0.04em;
        color: var(--text-primary); margin-bottom: 4px;
    }
    .stat-card h3 {
        font-size: 0.875rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px;
    }
    .stat-card .stat-link {
        font-size: 0.8rem; font-weight: 600; text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px;
        transition: var(--transition);
    }
    .stat-card .stat-link:hover { opacity: 0.8; }
    .stat-card .stat-link svg { width: 14px; height: 14px; transition: var(--transition); }
    .stat-card .stat-link:hover svg { transform: translateX(3px); }

    /* ── Table card (matches product page) ── */
    .table-card {
        background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden;
    }
    .table-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px;
    }
    .table-toolbar .section-label {
        font-size: 1rem; font-weight: 700; color: var(--text-primary);
        display: flex; align-items: center; gap: 8px;
    }
    .table-toolbar .section-label svg { width: 18px; height: 18px; color: var(--accent-1); }
    .table-count { font-size: 0.8rem; color: var(--text-muted); }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--bg-elevated); }
    thead th {
        padding: 12px 20px; text-align: left; font-size: 0.75rem; font-weight: 700;
        letter-spacing: 0.06em; text-transform: uppercase; color: var(--text-muted);
        border-bottom: 1px solid var(--border);
    }
    tbody tr { border-bottom: 1px solid var(--border); transition: background var(--transition); }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--bg-elevated); }
    td {
        padding: 14px 20px; font-size: 0.875rem; color: var(--text-secondary); vertical-align: middle;
    }

    .product-name {
        display: inline-flex; align-items: center; gap: 8px; color: var(--text-primary); font-weight: 600;
    }
    .prod-dot {
        width: 8px; height: 8px; border-radius: 999px; background: var(--accent-grad); flex-shrink: 0;
    }
    .pill {
        display: inline-flex; padding: 4px 10px; border-radius: 999px;
        background: var(--accent-soft); color: var(--accent-1); font-size: 0.75rem; font-weight: 700;
    }
    .dt { font-size: 0.78rem; color: var(--text-muted); }

    /* product thumbnail */
    .prod-thumb {
        width: 48px; height: 48px; border-radius: 8px; object-fit: cover;
        border: 1px solid var(--border); flex-shrink: 0;
    }
    .prod-thumb-placeholder {
        width: 48px; height: 48px; border-radius: 8px;
        background: var(--bg-elevated); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .prod-thumb-placeholder svg { width: 20px; height: 20px; color: var(--text-muted); }

    /* table footer link */
    .table-footer {
        padding: 14px 20px; border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-footer a {
        font-size: 0.82rem; font-weight: 600; color: var(--accent-1);
        text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
        transition: var(--transition);
    }
    .table-footer a svg { width: 14px; height: 14px; transition: var(--transition); }
    .table-footer a:hover svg { transform: translateX(3px); }

    /* ── Buttons (matches product page) ── */
    .btn {
        display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
        border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 600; cursor: pointer;
        transition: var(--transition); border: none; text-decoration: none; white-space: nowrap;
    }
    .btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-primary {
        background: var(--accent-grad); color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }

    /* ── Empty state ── */
    .empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 60px 20px; gap: 14px; color: var(--text-muted);
    }
    .empty-icon {
        width: 64px; height: 64px; border-radius: 50%; background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
    }
    .empty-icon svg { width: 28px; height: 28px; color: var(--accent-1); }
    .empty-state h3 { font-size: 1.05rem; font-weight: 700; color: var(--text-secondary); }
    .empty-state p { font-size: 0.875rem; }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 1100px) and (min-width: 901px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="page-shell">

    {{-- Page header (same style as product page) --}}
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Overview of your product catalog, categories, and stock levels.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Product
        </a>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="stats-grid">

        {{-- Total Products --}}
        <div class="stat-card" id="card-total-products">
            <div class="stat-card-icon indigo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4m-2-4v8M3.75 7.5h16.5M10.5 3.75h3A2.25 2.25 0 0 1 15.75 6v1.5h-7.5V6A2.25 2.25 0 0 1 10.5 3.75Z"/>
                </svg>
            </div>
            <div class="stat-value" id="val-products">{{ number_format($totalProducts) }}</div>
            <h3>Total Products</h3>
            <a href="{{ route('products.index') }}" class="stat-link" style="color: var(--accent-1);">
                View all products
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

        {{-- Total Categories --}}
        <div class="stat-card" id="card-total-categories">
            <div class="stat-card-icon cyan">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
                </svg>
            </div>
            <div class="stat-value" id="val-categories">{{ number_format($totalCategories) }}</div>
            <h3>Total Categories</h3>
            <a href="{{ route('categories.index') }}" class="stat-link" style="color: var(--accent-3);">
                Manage categories
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

        {{-- Total Quantity --}}
        <div class="stat-card" id="card-total-quantity">
            <div class="stat-card-icon emerald">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3"/>
                </svg>
            </div>
            <div class="stat-value" id="val-quantity">{{ number_format($totalQuantity) }}</div>
            <h3>Total Stock Quantity</h3>
            <span style="font-size: 0.8rem; color: var(--text-muted);">Units across all products</span>
        </div>

    </div>

    {{-- ── Latest 5 Products (same table-card style as product page) ── --}}
    <div class="table-card" id="card-latest-products">
        <div class="table-toolbar">
            <div class="section-label">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                Latest 5 Products
            </div>
            <div class="table-count">
                Showing <strong>{{ $latestProducts->count() }}</strong> most recent {{ Str::plural('product', $latestProducts->count()) }}
            </div>
        </div>

        @if($latestProducts->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Added</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestProducts as $i => $product)
                        <tr>
                            <td class="dt">{{ $i + 1 }}</td>
                            <td>
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
                                <div class="product-name">
                                    <span class="prod-dot"></span>
                                    {{ $product->name }}
                                </div>
                            </td>
                            <td>
                                <span class="pill">{{ $product->category?->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td>${{ number_format($product->price ?? 0, 2) }}</td>
                            <td>{{ number_format($product->quantity ?? 0) }}</td>
                            <td class="dt">{{ $product->created_at?->format('M d, Y') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="table-footer">
                <a href="{{ route('products.index') }}">
                    View all products
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="{{ route('products.create') }}">
                    Add new product
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </a>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4m-2-4v8M3.75 7.5h16.5"/>
                    </svg>
                </div>
                <h3>No products yet</h3>
                <p>Create your first product to start building the catalog.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-top: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Product
                </a>
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Animated count-up for stat values on page load
    function countUp(el, target, duration = 900) {
        if (!el) return;
        const start = performance.now();
        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(target * ease).toLocaleString();
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', () => {
        countUp(document.getElementById('val-products'),   {{ $totalProducts }});
        countUp(document.getElementById('val-categories'), {{ $totalCategories }});
        countUp(document.getElementById('val-quantity'),   {{ $totalQuantity }});
    });
</script>
@endsection
