@extends('layouts.app')

@section('title', 'Products')
@section('meta_description', 'Manage your products and inventory.')

@section('styles')
<style>
    /* ── Page header ── */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 28px; flex-wrap: wrap; gap: 14px;
    }
    .page-header h1 {
        font-size: 1.7rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text-primary);
    }
    .page-header p { margin-top: 4px; color: var(--text-secondary); font-size: 0.9rem; }

    /* ── Buttons ── */
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
    .btn-ghost {
        background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border);
    }
    .btn-ghost:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .btn-danger {
        background: rgba(244,63,94,0.12); color: #f43f5e; border: 1px solid rgba(244,63,94,0.25);
    }
    .btn-danger:hover { background: rgba(244,63,94,0.22); border-color: rgba(244,63,94,0.5); }
    .btn-sm { padding: 6px 13px; font-size: 0.8rem; }
    .btn-active {
        background: var(--accent-soft); color: var(--accent-1);
        border: 1px solid rgba(99,102,241,0.35);
    }

    /* ── Alert ── */
    .alert {
        display: flex; align-items: center; gap: 12px; padding: 14px 18px;
        border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 500;
        margin-bottom: 22px; animation: slideDown 0.3s ease;
    }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .alert svg { width: 18px; height: 18px; flex-shrink: 0; }
    .alert-success {
        background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #10b981;
    }

    /* ── Search / filter bar ── */
    .filter-bar {
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        padding: 16px 20px; background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius-lg); margin-bottom: 20px;
    }
    .filter-bar .input-wrap {
        flex: 1; min-width: 200px; position: relative;
    }
    .filter-bar .input-wrap svg {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; color: var(--text-muted); pointer-events: none;
    }
    .filter-bar input[type="text"],
    .filter-bar select {
        width: 100%; padding: 9px 12px 9px 34px;
        background: var(--bg-elevated); border: 1px solid var(--border);
        border-radius: var(--radius-sm); color: var(--text-primary);
        font-size: 0.875rem; outline: none; transition: var(--transition);
        appearance: none; -webkit-appearance: none;
    }
    .filter-bar select { padding-left: 12px; padding-right: 32px; }
    .select-wrap {
        position: relative; min-width: 170px;
    }
    .select-wrap svg.chevron {
        position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
        width: 14px; height: 14px; color: var(--text-muted); pointer-events: none;
    }
    .filter-bar input[type="text"]:focus,
    .filter-bar select:focus {
        border-color: var(--accent-1); box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
    }

    .sort-group { display: flex; gap: 6px; }
    .sort-group a {
        padding: 8px 14px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;
        text-decoration: none; transition: var(--transition);
        border: 1px solid var(--border); color: var(--text-secondary); background: var(--bg-elevated);
    }
    .sort-group a:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .sort-group a.active {
        background: var(--accent-soft); color: var(--accent-1);
        border-color: rgba(99,102,241,0.35);
    }

    .filter-actions { display: flex; gap: 8px; }

    /* ── Table card ── */
    .table-card {
        background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden;
    }
    .table-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px;
    }
    .table-count { font-size: 0.8rem; color: var(--text-muted); }
    .table-count strong { color: var(--text-secondary); }

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
    .actions { display: flex; align-items: center; gap: 8px; }
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

    /* ── Pagination ── */
    .pagination-wrap {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 12px;
    }
    .pagination-info { font-size: 0.8rem; color: var(--text-muted); }
    .pagination-links { display: flex; gap: 4px; align-items: center; }
    .pagination-links a,
    .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 34px; height: 34px; padding: 0 8px;
        border-radius: var(--radius-sm); font-size: 0.82rem; font-weight: 600;
        text-decoration: none; transition: var(--transition);
    }
    .pagination-links a {
        background: var(--bg-elevated); border: 1px solid var(--border); color: var(--text-secondary);
    }
    .pagination-links a:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .pagination-links span.current {
        background: var(--accent-grad); color: #fff;
        border: 1px solid transparent;
        box-shadow: 0 2px 8px rgba(99,102,241,0.35);
    }
    .pagination-links span.disabled {
        background: var(--bg-elevated); border: 1px solid var(--border); color: var(--text-muted); opacity: 0.5;
    }
    .page-dots {
        color: var(--text-muted); font-size: 0.82rem; padding: 0 4px;
    }

    /* ── Empty state ── */
    .empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 60px 20px; gap: 14px; color: var(--text-muted);
        background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
    }
    .empty-icon {
        width: 64px; height: 64px; border-radius: 50%; background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
    }
    .empty-icon svg { width: 28px; height: 28px; color: var(--accent-1); }
    .empty-state h3 { font-size: 1.05rem; font-weight: 700; color: var(--text-secondary); }
    .empty-state p { font-size: 0.875rem; }

    /* search highlight */
    mark { background: rgba(99,102,241,0.2); color: var(--accent-1); border-radius: 3px; padding: 0 2px; }
</style>
@endsection

@section('content')
<div class="page-shell">
    {{-- Page header --}}
    <div class="page-header">
        <div>
            <h1>Products</h1>
            <p>Track your catalog, pricing, and stock levels in one place.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Product
        </a>
    </div>

    {{-- Flash messages --}}
    @include('partials.flash')

    {{-- ── Search / Filter / Sort bar ── --}}
    <form method="GET" action="{{ route('products.index') }}" id="filter-form">
        {{-- keep sort value when searching --}}
        @if(request('sort') && request('sort') !== 'latest')
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif

        <div class="filter-bar">
            {{-- Search by name --}}
            <div class="input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search products…"
                    autocomplete="off"
                >
            </div>

            {{-- Filter by category --}}
            <div class="select-wrap">
                <select id="category_id" name="category_id" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <svg class="chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </div>

            {{-- Sort toggle --}}
            <div class="sort-group">
                <a href="{{ route('products.index', array_merge(request()->except(['sort','page']), ['sort'=>'latest'])) }}"
                   class="{{ $sort === 'latest' ? 'active' : '' }}"
                   title="Sort: Newest first">
                    ↓ Latest
                </a>
                <a href="{{ route('products.index', array_merge(request()->except(['sort','page']), ['sort'=>'oldest'])) }}"
                   class="{{ $sort === 'oldest' ? 'active' : '' }}"
                   title="Sort: Oldest first">
                    ↑ Oldest
                </a>
            </div>

            {{-- Search / Clear --}}
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    Search
                </button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('products.index') }}" class="btn btn-ghost btn-sm">Clear</a>
                @endif
            </div>
        </div>
    </form>

    {{-- ── Product table ── --}}
    @if($products->count())
        <div class="table-card">
            <div class="table-toolbar">
                <div class="table-count">
                    Showing <strong>{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong>
                    of <strong>{{ $products->total() }}</strong>
                    {{ Str::plural('product', $products->total()) }}
                    @if(request('search'))
                        &nbsp;for &ldquo;<em>{{ request('search') }}</em>&rdquo;
                    @endif
                    @if(request('category_id'))
                        &nbsp;· filtered by category
                    @endif
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $i => $product)
                        <tr>
                            <td class="dt">{{ $products->firstItem() + $i }}</td>
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
                                    @if(request('search'))
                                        {!! str_ireplace(
                                            e(request('search')),
                                            '<mark>' . e(request('search')) . '</mark>',
                                            e($product->name)
                                        ) !!}
                                    @else
                                        {{ $product->name }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="pill">{{ $product->category?->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td>${{ number_format($product->price ?? 0, 2) }}</td>
                            <td>{{ $product->quantity ?? 0 }}</td>
                            <td class="dt">{{ $product->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost btn-sm"
                                       id="btn-edit-prod-{{ $product->id }}">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                            id="btn-del-prod-{{ $product->id }}"
                                            onclick="openDeleteModal(
                                                '{{ route('products.destroy', $product) }}',
                                                '{{ addslashes($product->name) }}'
                                            )">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- ── Pagination ── --}}
            @if($products->hasPages())
                <div class="pagination-wrap">
                    <div class="pagination-info">
                        Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                    </div>

                    <div class="pagination-links">
                        {{-- Previous --}}
                        @if($products->onFirstPage())
                            <span class="disabled" aria-disabled="true">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" rel="prev" aria-label="Previous page">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="current" aria-current="page">{{ $page }}</span>
                            @elseif(
                                $page == 1 ||
                                $page == $products->lastPage() ||
                                abs($page - $products->currentPage()) <= 2
                            )
                                <a href="{{ $url }}">{{ $page }}</a>
                            @elseif(abs($page - $products->currentPage()) == 3)
                                <span class="page-dots">…</span>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" rel="next" aria-label="Next page">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        @else
                            <span class="disabled" aria-disabled="true">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    @else
        {{-- No results --}}
        <div class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </div>
            @if(request('search') || request('category_id'))
                <h3>No products found</h3>
                <p>Try a different search term or category, or <a href="{{ route('products.index') }}" style="color:var(--accent-1)">clear the filters</a>.</p>
            @else
                <h3>No products yet</h3>
                <p>Create your first product to start building the catalog.</p>
            @endif
        </div>
    @endif
</div>

@include('partials.delete-modal')
@endsection