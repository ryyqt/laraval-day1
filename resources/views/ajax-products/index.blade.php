@extends('layouts.app')

@section('title', 'Day 9 – AJAX Products')
@section('meta_description', 'Day 9: Load products via AJAX from API. Full CRUD without page refresh.')

{{-- ══════════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════════ --}}
@section('styles')
<style>
    /* ── Page-level tokens ───────────────────────── */
    :root {
        --green:   #10b981;
        --yellow:  #f59e0b;
        --rose:    #f43f5e;
        --sky:     #0ea5e9;
    }

    /* ── Page header ─────────────────────────────── */
    .ajax-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }
    .ajax-header-text h1 {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -.03em;
    }
    .ajax-header-text p {
        margin-top: 4px;
        color: var(--text-secondary);
        font-size: .9rem;
    }
    .day-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--accent-soft);
        color: var(--accent-1);
        border: 1px solid rgba(99,102,241,.25);
        border-radius: 999px;
        padding: 4px 14px;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    /* ── Toolbar (search + filters + add btn) ────── */
    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        margin-bottom: 22px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 14px 18px;
    }
    .toolbar-search {
        flex: 1;
        min-width: 180px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 8px 14px;
        transition: var(--transition);
    }
    .toolbar-search:focus-within {
        border-color: var(--border-hover);
    }
    .toolbar-search svg { width:16px;height:16px;color:var(--text-muted);flex-shrink:0; }
    .toolbar-search input {
        border: 0;
        background: transparent;
        color: var(--text-primary);
        font-size: .875rem;
        outline: none;
        width: 100%;
    }
    .toolbar-search input::placeholder { color: var(--text-muted); }

    .toolbar-select {
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        font-size: .875rem;
        padding: 8px 12px;
        outline: none;
        transition: var(--transition);
        cursor: pointer;
    }
    .toolbar-select:focus { border-color: var(--border-hover); }

    /* ── Buttons ─────────────────────────────────── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        font-size: .875rem;
        font-weight: 600;
        border: 0;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }
    .btn svg { width:16px;height:16px;flex-shrink:0; }
    .btn-primary {
        background: var(--accent-grad);
        color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,.4);
    }
    .btn-primary:hover { opacity:.9; transform:translateY(-1px); }
    .btn-danger  { background: rgba(244,63,94,.12); color: var(--rose); border:1px solid rgba(244,63,94,.25); }
    .btn-danger:hover  { background: rgba(244,63,94,.22); }
    .btn-warning { background: rgba(245,158,11,.12); color: var(--yellow); border:1px solid rgba(245,158,11,.25); }
    .btn-warning:hover { background: rgba(245,158,11,.22); }
    .btn-ghost   { background: var(--bg-elevated); color: var(--text-secondary); border:1px solid var(--border); }
    .btn-ghost:hover   { border-color: var(--border-hover); color: var(--text-primary); }
    .btn-sm { padding: 6px 12px; font-size: .8rem; }
    .btn:disabled { opacity:.5; cursor:not-allowed; pointer-events:none; }

    /* ── Stats row ───────────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px 20px;
        transition: var(--transition);
    }
    .stat-card:hover { border-color: var(--border-hover); transform: translateY(-2px); }
    .stat-label { font-size: .75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
    .stat-val   { font-size: 1.8rem; font-weight: 800; letter-spacing: -.04em; }
    .stat-val.indigo { color: var(--accent-1); }
    .stat-val.green  { color: var(--green); }
    .stat-val.yellow { color: var(--yellow); }
    .stat-val.sky    { color: var(--sky); }

    /* ── Product grid ────────────────────────────── */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 18px;
    }
    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        animation: fadeUp .35s ease both;
    }
    @keyframes fadeUp {
        from { opacity:0; transform:translateY(14px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .product-card:hover { border-color: var(--border-hover); box-shadow: var(--shadow-glow); transform: translateY(-3px); }
    .product-img-wrap {
        height: 160px;
        background: var(--bg-elevated);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .product-img-wrap img { width:100%;height:100%;object-fit:cover; }
    .product-img-placeholder {
        width: 56px; height: 56px;
        border-radius: var(--radius-md);
        background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
    }
    .product-img-placeholder svg { width:28px;height:28px;color:var(--accent-1); }
    .product-cat-badge {
        position: absolute;
        top: 10px; left: 10px;
        background: rgba(13,15,26,.75);
        backdrop-filter: blur(8px);
        border: 1px solid var(--border);
        border-radius: 999px;
        padding: 2px 10px;
        font-size: .7rem;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .product-body { padding: 16px; flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .product-name { font-size: 1rem; font-weight: 700; color: var(--text-primary); line-height: 1.3; }
    .product-desc { font-size: .8rem; color: var(--text-muted); line-height: 1.5; flex: 1; }
    .product-meta { display: flex; align-items: center; justify-content: space-between; }
    .product-price {
        font-size: 1.15rem; font-weight: 800;
        background: var(--accent-grad);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .product-qty {
        font-size: .78rem; color: var(--text-muted);
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        border-radius: 999px;
        padding: 2px 8px;
    }
    .product-actions {
        display: flex;
        gap: 8px;
        padding: 12px 16px;
        border-top: 1px solid var(--border);
        background: var(--bg-elevated);
    }
    .product-actions .btn { flex: 1; justify-content: center; }

    /* ── Pagination ──────────────────────────────── */
    .pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }
    .pagination-info { font-size: .85rem; color: var(--text-muted); }
    .pagination-btns { display: flex; gap: 6px; }
    .page-btn {
        min-width: 36px; height: 36px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-secondary);
        font-size: .85rem; font-weight: 600;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: var(--transition);
        padding: 0 10px;
    }
    .page-btn:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .page-btn.active { background: var(--accent-grad); color:#fff; border-color:transparent; }
    .page-btn:disabled { opacity:.35; cursor:not-allowed; }

    /* ── Skeleton loader ─────────────────────────── */
    .skeleton {
        background: linear-gradient(90deg, var(--bg-elevated) 25%, var(--bg-card) 50%, var(--bg-elevated) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s infinite;
        border-radius: var(--radius-sm);
    }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
    .skeleton-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .sk-img { height: 160px; }
    .sk-body { padding: 16px; display: flex; flex-direction: column; gap: 10px; }
    .sk-line { height: 12px; border-radius: 6px; }

    /* ── Empty state ─────────────────────────────── */
    .empty-state {
        grid-column: 1/-1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        gap: 12px;
    }
    .empty-icon {
        width: 64px; height: 64px;
        border-radius: var(--radius-lg);
        background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 4px;
    }
    .empty-icon svg { width:32px;height:32px;color:var(--accent-1); }
    .empty-state h3 { font-size:1.1rem;font-weight:700;color:var(--text-primary); }
    .empty-state p  { font-size:.875rem;color:var(--text-muted);max-width:320px;line-height:1.6; }

    /* ── Toast notification ──────────────────────── */
    #toast-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }
    .toast {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 18px;
        min-width: 280px;
        box-shadow: var(--shadow-card);
        animation: slideUp .3s ease;
        pointer-events: auto;
        transition: opacity .3s ease;
        font-size: .875rem;
        font-weight: 500;
    }
    @keyframes slideUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
    .toast.success { border-left: 3px solid var(--green); color: var(--green); }
    .toast.error   { border-left: 3px solid var(--rose);  color: var(--rose); }
    .toast.info    { border-left: 3px solid var(--accent-1); color: var(--accent-1); }
    .toast-icon { flex-shrink:0; width:18px;height:18px; }
    .toast-msg  { flex:1; color: var(--text-primary); }

    /* ── Modal ───────────────────────────────────── */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.7);
        backdrop-filter: blur(6px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
    }
    .modal-backdrop.open { opacity: 1; pointer-events: auto; }
    .modal {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        width: 100%;
        max-width: 540px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 24px 80px rgba(0,0,0,.6);
        transform: scale(.95) translateY(20px);
        transition: transform .25s ease;
    }
    .modal-backdrop.open .modal { transform: scale(1) translateY(0); }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 24px 18px;
        border-bottom: 1px solid var(--border);
    }
    .modal-title { font-size: 1.1rem; font-weight: 800; }
    .modal-close {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--bg-elevated);
        color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }
    .modal-close:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .modal-close svg { width:16px;height:16px; }
    .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 18px; }
    .modal-footer {
        padding: 18px 24px 22px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    /* ── Form controls ───────────────────────────── */
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-label { font-size: .82rem; font-weight: 600; color: var(--text-secondary); }
    .form-control {
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        font-size: .875rem;
        padding: 9px 12px;
        outline: none;
        transition: var(--transition);
        width: 100%;
    }
    .form-control:focus { border-color: var(--border-hover); background: var(--bg-card); }
    .form-control::placeholder { color: var(--text-muted); }
    textarea.form-control { resize: vertical; min-height: 80px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-error { font-size: .78rem; color: var(--rose); margin-top: 2px; }
    .form-hint { font-size: .75rem; color: var(--text-muted); }

    /* ── Delete confirm modal ────────────────────── */
    .delete-modal .modal { max-width: 420px; }
    .delete-body { padding:28px 24px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:14px; }
    .delete-icon-wrap {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: rgba(244,63,94,.12);
        display: flex; align-items: center; justify-content: center;
    }
    .delete-icon-wrap svg { width:28px;height:28px;color:var(--rose); }
    .delete-body h2 { font-size:1.15rem;font-weight:800;color:var(--text-primary); }
    .delete-body p  { font-size:.875rem;color:var(--text-muted);line-height:1.6; }

    /* ── API tag ─────────────────────────────────── */
    .api-tag {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        background: rgba(6,182,212,.12);
        color: var(--accent-3);
        border: 1px solid rgba(6,182,212,.2);
        border-radius: 999px;
        padding: 2px 10px;
    }
    .ajax-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .75rem;
        color: var(--green);
        font-weight: 600;
    }
    .dot-pulse {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--green);
        animation: pulse 1.5s ease infinite;
    }
    @keyframes pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.4);opacity:.7} }

    /* ── Image preview in modal ──────────────────── */
    .img-preview-wrap {
        width: 100%;
        height: 130px;
        border: 2px dashed var(--border);
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        position: relative;
        transition: var(--transition);
        cursor: pointer;
    }
    .img-preview-wrap:hover { border-color: var(--border-hover); }
    .img-preview-wrap img { width:100%; height:100%; object-fit:cover; }
    .img-preview-placeholder { display:flex; flex-direction:column; align-items:center; gap:6px; color:var(--text-muted); font-size:.8rem; }
    .img-preview-placeholder svg { width:28px;height:28px; }
    #product-image { display:none; }

    @media (max-width: 640px) {
        .form-row { grid-template-columns: 1fr; }
        .pagination-wrap { justify-content: center; }
        .ajax-header { flex-direction: column; }
    }
</style>
@endsection

{{-- ══════════════════════════════════════════════════
     CONTENT
══════════════════════════════════════════════════ --}}
@section('content')

{{-- Page Header --}}
<div class="ajax-header">
    <div class="ajax-header-text">
        <div class="day-badge">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Day 9
        </div>
        <h1>AJAX Products <span class="api-tag">REST API</span></h1>
        <p>Full CRUD via AJAX — no page refresh. Powered by <code style="background:var(--bg-elevated);padding:1px 6px;border-radius:4px;font-size:.8em">/api/products</code></p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <span class="ajax-indicator">
            <span class="dot-pulse" id="ajax-indicator-dot"></span>
            <span id="ajax-status-text">Ready</span>
        </span>
        <button class="btn btn-primary" id="btn-add-product">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Add Product
        </button>
    </div>
</div>

{{-- Stats Row --}}
<div class="stats-row" id="stats-row">
    <div class="stat-card">
        <div class="stat-label">Total Products</div>
        <div class="stat-val indigo" id="stat-total">—</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Value</div>
        <div class="stat-val green" id="stat-value">—</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Avg. Price</div>
        <div class="stat-val yellow" id="stat-avg">—</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Categories</div>
        <div class="stat-val sky" id="stat-cats">{{ $categories->count() }}</div>
    </div>
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <div class="toolbar-search">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
        <input type="text" id="search-input" placeholder="Search products…" autocomplete="off" aria-label="Search products">
    </div>

    <select class="toolbar-select" id="filter-category" aria-label="Filter by category">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>

    <select class="toolbar-select" id="filter-sort" aria-label="Sort order">
        <option value="latest">Newest First</option>
        <option value="oldest">Oldest First</option>
    </select>

    <select class="toolbar-select" id="filter-per-page" aria-label="Items per page">
        <option value="8">8 per page</option>
        <option value="12" selected>12 per page</option>
        <option value="24">24 per page</option>
    </select>

    <button class="btn btn-ghost btn-sm" id="btn-refresh" title="Reload from API">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
        </svg>
        Refresh
    </button>
</div>

{{-- Product Grid --}}
<div class="product-grid" id="product-grid">
    {{-- Filled by JS --}}
</div>

{{-- Pagination --}}
<div class="pagination-wrap" id="pagination-wrap"></div>

{{-- ══════ ADD / EDIT MODAL ══════ --}}
<div class="modal-backdrop" id="product-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-text">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="modal-title-text">Add Product</span>
            <button class="modal-close" id="modal-close-btn" aria-label="Close modal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="product-form" novalidate>
            @csrf
            <input type="hidden" id="product-id">
            <div class="modal-body">
                {{-- Image --}}
                <div class="form-group">
                    <label class="form-label" for="product-image">Product Image</label>
                    <div class="img-preview-wrap" id="img-preview-wrap" role="button" tabindex="0" aria-label="Click to upload image">
                        <img id="img-preview" src="" alt="" style="display:none">
                        <div class="img-preview-placeholder" id="img-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                            <span>Click to upload image</span>
                            <span style="font-size:.7rem;opacity:.6">JPG, PNG, WebP · Max 2MB</span>
                        </div>
                    </div>
                    <input type="file" id="product-image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
                    <div id="remove-img-wrap" style="display:none">
                        <label style="display:flex;align-items:center;gap:6px;font-size:.8rem;color:var(--text-muted);cursor:pointer;margin-top:4px">
                            <input type="checkbox" id="remove-image" style="accent-color:var(--rose)"> Remove current image
                        </label>
                    </div>
                </div>

                {{-- Name --}}
                <div class="form-group">
                    <label class="form-label" for="product-name">Product Name <span style="color:var(--rose)">*</span></label>
                    <input type="text" class="form-control" id="product-name" name="name" placeholder="e.g. Premium Wireless Headphones" required>
                    <div class="form-error" id="err-name"></div>
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label class="form-label" for="product-category">Category</label>
                    <select class="form-control" id="product-category" name="category_id">
                        <option value="">— No Category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-error" id="err-category_id"></div>
                </div>

                {{-- Price & Quantity --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="product-price">Price ($) <span style="color:var(--rose)">*</span></label>
                        <input type="number" class="form-control" id="product-price" name="price" placeholder="0.00" step="0.01" min="0" required>
                        <div class="form-error" id="err-price"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="product-quantity">Quantity <span style="color:var(--rose)">*</span></label>
                        <input type="number" class="form-control" id="product-quantity" name="quantity" placeholder="0" min="0" required>
                        <div class="form-error" id="err-quantity"></div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label" for="product-description">Description</label>
                    <textarea class="form-control" id="product-description" name="description" placeholder="Optional product description…" rows="3"></textarea>
                    <div class="form-error" id="err-description"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" id="modal-cancel-btn">Cancel</button>
                <button type="submit" class="btn btn-primary" id="modal-submit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span id="submit-label">Add Product</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════ DELETE CONFIRM MODAL ══════ --}}
<div class="modal-backdrop delete-modal" id="delete-modal" role="dialog" aria-modal="true" aria-labelledby="delete-title">
    <div class="modal">
        <div class="delete-body">
            <div class="delete-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
            </div>
            <h2 id="delete-title">Delete Product?</h2>
            <p>Are you sure you want to delete <strong id="delete-product-name">"Product"</strong>? This action cannot be undone.</p>
            <div style="display:flex;gap:10px;width:100%;margin-top:4px">
                <button class="btn btn-ghost" id="delete-cancel-btn" style="flex:1">Cancel</button>
                <button class="btn btn-danger" id="delete-confirm-btn" style="flex:1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Toast container --}}
<div id="toast-container" aria-live="polite" aria-atomic="false"></div>

@endsection

{{-- ══════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════ --}}
@section('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   Day 9 – AJAX CRUD   |   /api/products  (Laravel REST API)
═══════════════════════════════════════════════════════════ */

const API_BASE = '/api/products';

/* ── State ───────────────────────────────────────────────── */
let state = {
    page:      1,
    perPage:   12,
    search:    '',
    category:  '',
    sort:      'latest',
    total:     0,
    lastPage:  1,
    loading:   false,
    deleteId:  null,
    deleteName: '',
    editId:    null,
};

/* ── DOM refs ────────────────────────────────────────────── */
const grid          = document.getElementById('product-grid');
const paginationWrap= document.getElementById('pagination-wrap');
const searchInput   = document.getElementById('search-input');
const filterCat     = document.getElementById('filter-category');
const filterSort    = document.getElementById('filter-sort');
const filterPerPage = document.getElementById('filter-per-page');
const btnRefresh    = document.getElementById('btn-refresh');
const btnAdd        = document.getElementById('btn-add-product');
const ajaxDot       = document.getElementById('ajax-indicator-dot');
const ajaxStatusTxt = document.getElementById('ajax-status-text');

/* ── CSRF token (from meta or form) ─────────────────────── */
function getCsrf() {
    const m = document.querySelector('meta[name="csrf-token"]');
    if (m) return m.getAttribute('content');
    const f = document.querySelector('input[name="_token"]');
    return f ? f.value : '';
}

/* ── AJAX status indicator ───────────────────────────────── */
function setAjaxStatus(loading) {
    state.loading = loading;
    if (loading) {
        ajaxDot.style.background = 'var(--yellow)';
        ajaxStatusTxt.textContent = 'Loading…';
        btnRefresh.disabled = true;
    } else {
        ajaxDot.style.background = 'var(--green)';
        ajaxStatusTxt.textContent = 'Ready';
        btnRefresh.disabled = false;
    }
}

/* ── Toast ───────────────────────────────────────────────── */
function toast(msg, type = 'info') {
    const icons = {
        success: `<svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>`,
        error:   `<svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>`,
        info:    `<svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>`,
    };
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.innerHTML = `${icons[type]}<span class="toast-msg">${msg}</span>`;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 320); }, 3500);
}

/* ── Skeleton loader ─────────────────────────────────────── */
function renderSkeletons(n = 8) {
    grid.innerHTML = Array(n).fill(0).map(() => `
        <div class="skeleton-card">
            <div class="skeleton sk-img"></div>
            <div class="sk-body">
                <div class="skeleton sk-line" style="width:70%"></div>
                <div class="skeleton sk-line" style="width:45%"></div>
                <div class="skeleton sk-line" style="width:55%;height:8px"></div>
                <div class="skeleton sk-line" style="width:30%;height:8px"></div>
            </div>
        </div>
    `).join('');
}

/* ── Fetch products (GET /api/products) ──────────────────── */
async function fetchProducts() {
    setAjaxStatus(true);
    renderSkeletons(state.perPage > 12 ? 12 : state.perPage);

    const params = new URLSearchParams({
        page:     state.page,
        per_page: state.perPage,
        sort:     state.sort,
    });
    if (state.search)   params.set('search',      state.search);
    if (state.category) params.set('category_id', state.category);

    try {
        const res  = await fetch(`${API_BASE}?${params}`);
        const json = await res.json();

        if (!res.ok || !json.success) throw new Error(json.message || 'Failed to load products');

        const { data } = json;
        state.total    = data.total;
        state.lastPage = data.last_page;

        updateStats(data.data);
        renderGrid(data.data);
        renderPagination(data);
    } catch (err) {
        grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1">
            <div class="empty-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="color:var(--rose)"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg></div>
            <h3>Failed to Load</h3>
            <p>${err.message}</p>
            <button class="btn btn-ghost" onclick="fetchProducts()">Try Again</button>
        </div>`;
        toast(err.message, 'error');
    } finally {
        setAjaxStatus(false);
    }
}

/* ── Update stats ────────────────────────────────────────── */
function updateStats(products) {
    document.getElementById('stat-total').textContent = state.total;

    const totalVal = products.reduce((s, p) => s + (parseFloat(p.price) * parseInt(p.quantity || 0)), 0);
    const avgPrice = products.length ? products.reduce((s, p) => s + parseFloat(p.price), 0) / products.length : 0;

    document.getElementById('stat-value').textContent = '$' + totalVal.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
    document.getElementById('stat-avg').textContent   = '$' + avgPrice.toFixed(2);
}

/* ── Render product cards ────────────────────────────────── */
function renderGrid(products) {
    if (!products.length) {
        grid.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375v11.25a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 17.625V6.375m17.25 0A2.25 2.25 0 0 0 18 4.125H6a2.25 2.25 0 0 0-2.25 2.25m17.25 0v.75A2.25 2.25 0 0 1 18 9.375H6a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                    </svg>
                </div>
                <h3>No Products Found</h3>
                <p>Try adjusting your search or filters, or add a new product.</p>
                <button class="btn btn-primary" id="empty-add-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Product
                </button>
            </div>`;
        document.getElementById('empty-add-btn')?.addEventListener('click', openAddModal);
        return;
    }

    grid.innerHTML = products.map((p, i) => {
        const imgSrc = p.image
            ? `/storage/${p.image}`
            : null;
        const catName = p.category?.name ?? '';
        const desc = p.description
            ? (p.description.length > 70 ? p.description.slice(0, 70) + '…' : p.description)
            : '<em style="opacity:.5">No description</em>';

        return `
        <div class="product-card" style="animation-delay:${i * 40}ms" data-id="${p.id}">
            <div class="product-img-wrap">
                ${imgSrc
                    ? `<img src="${imgSrc}" alt="${escHtml(p.name)}" loading="lazy">`
                    : `<div class="product-img-placeholder"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg></div>`
                }
                ${catName ? `<span class="product-cat-badge">${escHtml(catName)}</span>` : ''}
            </div>
            <div class="product-body">
                <div class="product-name">${escHtml(p.name)}</div>
                <div class="product-desc">${desc}</div>
                <div class="product-meta">
                    <span class="product-price">$${parseFloat(p.price).toFixed(2)}</span>
                    <span class="product-qty">Qty: ${p.quantity}</span>
                </div>
            </div>
            <div class="product-actions">
                <button class="btn btn-warning btn-sm btn-edit" data-id="${p.id}" aria-label="Edit ${escHtml(p.name)}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                    Edit
                </button>
                <button class="btn btn-danger btn-sm btn-delete" data-id="${p.id}" data-name="${escHtml(p.name)}" aria-label="Delete ${escHtml(p.name)}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                    Delete
                </button>
            </div>
        </div>`;
    }).join('');

    /* Bind card buttons */
    grid.querySelectorAll('.btn-edit').forEach(btn =>
        btn.addEventListener('click', () => openEditModal(btn.dataset.id))
    );
    grid.querySelectorAll('.btn-delete').forEach(btn =>
        btn.addEventListener('click', () => openDeleteModal(btn.dataset.id, btn.dataset.name))
    );
}

/* ── Pagination ──────────────────────────────────────────── */
function renderPagination(data) {
    const { current_page, last_page, from, to, total } = data;
    paginationWrap.innerHTML = '';
    if (last_page <= 1) return;

    const info = document.createElement('span');
    info.className = 'pagination-info';
    info.textContent = `Showing ${from}–${to} of ${total} products`;
    paginationWrap.appendChild(info);

    const btns = document.createElement('div');
    btns.className = 'pagination-btns';

    const prev = makePageBtn('‹', current_page - 1, current_page === 1);
    btns.appendChild(prev);

    const start = Math.max(1, current_page - 2);
    const end   = Math.min(last_page, current_page + 2);
    if (start > 1) { btns.appendChild(makePageBtn('1', 1, false, current_page === 1)); if (start > 2) btns.appendChild(ellipsis()); }

    for (let p = start; p <= end; p++) {
        const b = makePageBtn(p, p, false, p === current_page);
        btns.appendChild(b);
    }

    if (end < last_page) { if (end < last_page - 1) btns.appendChild(ellipsis()); btns.appendChild(makePageBtn(last_page, last_page, false, current_page === last_page)); }

    const next = makePageBtn('›', current_page + 1, current_page === last_page);
    btns.appendChild(next);

    paginationWrap.appendChild(btns);
}

function makePageBtn(label, page, disabled = false, active = false) {
    const b = document.createElement('button');
    b.className = 'page-btn' + (active ? ' active' : '');
    b.textContent = label;
    b.disabled = disabled;
    b.addEventListener('click', () => { state.page = page; fetchProducts(); });
    return b;
}
function ellipsis() {
    const s = document.createElement('span');
    s.className = 'page-btn';
    s.textContent = '…';
    s.style.cursor = 'default';
    s.style.pointerEvents = 'none';
    return s;
}

/* ── HTML escape ─────────────────────────────────────────── */
function escHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

/* ══════════════════════════════════════════════════════════
   ADD / EDIT MODAL
══════════════════════════════════════════════════════════ */
const productModal   = document.getElementById('product-modal');
const productForm    = document.getElementById('product-form');
const modalTitleText = document.getElementById('modal-title-text');
const submitLabel    = document.getElementById('submit-label');
const submitBtn      = document.getElementById('modal-submit-btn');
const productIdInput = document.getElementById('product-id');

function openModal() { productModal.classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeModal() {
    productModal.classList.remove('open');
    document.body.style.overflow = '';
    resetForm();
}

function resetForm() {
    productForm.reset();
    productIdInput.value  = '';
    state.editId = null;
    clearErrors();
    resetImagePreview();
    document.getElementById('remove-img-wrap').style.display = 'none';
}

function clearErrors() {
    ['name','category_id','price','quantity','description'].forEach(f => {
        const el = document.getElementById(`err-${f}`);
        if (el) el.textContent = '';
    });
}

function showErrors(errors) {
    clearErrors();
    Object.entries(errors).forEach(([field, msgs]) => {
        const el = document.getElementById(`err-${field}`);
        if (el) el.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
    });
}

function openAddModal() {
    state.editId = null;
    modalTitleText.textContent = 'Add Product';
    submitLabel.textContent    = 'Add Product';
    resetForm();
    openModal();
    document.getElementById('product-name').focus();
}

async function openEditModal(id) {
    state.editId = id;
    modalTitleText.textContent = 'Edit Product';
    submitLabel.textContent    = 'Save Changes';
    resetForm();
    openModal();

    try {
        submitBtn.disabled = true;
        const res  = await fetch(`${API_BASE}/${id}`);
        const json = await res.json();
        if (!res.ok) throw new Error('Failed to load product');

        const p = json.data;
        productIdInput.value = p.id;
        document.getElementById('product-name').value        = p.name ?? '';
        document.getElementById('product-category').value    = p.category_id ?? '';
        document.getElementById('product-price').value       = p.price ?? '';
        document.getElementById('product-quantity').value    = p.quantity ?? '';
        document.getElementById('product-description').value = p.description ?? '';

        if (p.image) {
            const img = document.getElementById('img-preview');
            img.src = `/storage/${p.image}`;
            img.style.display = 'block';
            document.getElementById('img-placeholder').style.display = 'none';
            document.getElementById('remove-img-wrap').style.display = 'block';
        }
    } catch (err) {
        toast('Failed to load product data', 'error');
        closeModal();
    } finally {
        submitBtn.disabled = false;
    }
}

/* ── Form submit (CREATE or UPDATE) ──────────────────────── */
productForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    clearErrors();

    const id  = productIdInput.value;
    const fd  = new FormData(productForm);
    const isEdit = !!id;

    if (isEdit) { fd.append('_method', 'PUT'); }

    submitBtn.disabled = true;
    submitLabel.textContent = isEdit ? 'Saving…' : 'Adding…';

    try {
        const url = isEdit ? `${API_BASE}/${id}` : API_BASE;
        const res = await fetch(url, {
            method: 'POST',   // Always POST; _method=PUT for update
            headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'application/json' },
            body: fd,
        });
        const json = await res.json();

        if (res.status === 422) { showErrors(json.errors ?? {}); return; }
        if (!res.ok) throw new Error(json.message || 'Request failed');

        closeModal();
        toast(json.message || (isEdit ? 'Product updated!' : 'Product added!'), 'success');
        state.page = isEdit ? state.page : 1;
        fetchProducts();
    } catch (err) {
        toast(err.message, 'error');
    } finally {
        submitBtn.disabled = false;
        submitLabel.textContent = isEdit ? 'Save Changes' : 'Add Product';
    }
});

/* ══════════════════════════════════════════════════════════
   DELETE MODAL
══════════════════════════════════════════════════════════ */
const deleteModal   = document.getElementById('delete-modal');
const deleteConfBtn = document.getElementById('delete-confirm-btn');

function openDeleteModal(id, name) {
    state.deleteId   = id;
    state.deleteName = name;
    document.getElementById('delete-product-name').textContent = `"${name}"`;
    deleteModal.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    deleteModal.classList.remove('open');
    document.body.style.overflow = '';
    state.deleteId   = null;
    state.deleteName = '';
}

document.getElementById('delete-cancel-btn').addEventListener('click', closeDeleteModal);
deleteModal.addEventListener('click', e => { if (e.target === deleteModal) closeDeleteModal(); });

deleteConfBtn.addEventListener('click', async () => {
    if (!state.deleteId) return;
    deleteConfBtn.disabled = true;
    deleteConfBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Deleting…';

    try {
        const res  = await fetch(`${API_BASE}/${state.deleteId}`, {
            method:  'DELETE',
            headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'application/json' },
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Delete failed');

        closeDeleteModal();
        toast(json.message || 'Product deleted.', 'success');
        if (state.page > 1 && state.total - 1 <= (state.page - 1) * state.perPage) state.page--;
        fetchProducts();
    } catch (err) {
        toast(err.message, 'error');
    } finally {
        deleteConfBtn.disabled = false;
        deleteConfBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg> Delete';
    }
});

/* ══════════════════════════════════════════════════════════
   IMAGE PREVIEW
══════════════════════════════════════════════════════════ */
const imgPreviewWrap = document.getElementById('img-preview-wrap');
const imgPreview     = document.getElementById('img-preview');
const imgPlaceholder = document.getElementById('img-placeholder');
const fileInput      = document.getElementById('product-image');

imgPreviewWrap.addEventListener('click', () => fileInput.click());
imgPreviewWrap.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') fileInput.click(); });

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        imgPreview.src = ev.target.result;
        imgPreview.style.display = 'block';
        imgPlaceholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

document.getElementById('remove-image').addEventListener('change', function() {
    if (this.checked) {
        imgPreview.style.display = 'none';
        imgPlaceholder.style.display = 'flex';
    } else {
        if (imgPreview.src) { imgPreview.style.display = 'block'; imgPlaceholder.style.display = 'none'; }
    }
});

function resetImagePreview() {
    imgPreview.src = '';
    imgPreview.style.display = 'none';
    imgPlaceholder.style.display = 'flex';
    document.getElementById('remove-image').checked = false;
}

/* ══════════════════════════════════════════════════════════
   EVENT BINDINGS
══════════════════════════════════════════════════════════ */

/* Add product */
btnAdd.addEventListener('click', openAddModal);

/* Close modal */
document.getElementById('modal-close-btn').addEventListener('click', closeModal);
document.getElementById('modal-cancel-btn').addEventListener('click', closeModal);
productModal.addEventListener('click', e => { if (e.target === productModal) closeModal(); });

/* Escape key */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (productModal.classList.contains('open')) closeModal();
        if (deleteModal.classList.contains('open'))  closeDeleteModal();
    }
});

/* Refresh */
btnRefresh.addEventListener('click', fetchProducts);

/* Search with debounce */
let searchDebounce;
searchInput.addEventListener('input', () => {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => {
        state.search = searchInput.value.trim();
        state.page   = 1;
        fetchProducts();
    }, 420);
});

/* Filters */
filterCat.addEventListener('change', () => { state.category = filterCat.value; state.page = 1; fetchProducts(); });
filterSort.addEventListener('change', () => { state.sort = filterSort.value; state.page = 1; fetchProducts(); });
filterPerPage.addEventListener('change', () => { state.perPage = parseInt(filterPerPage.value); state.page = 1; fetchProducts(); });

/* ── Spin keyframe for delete loading icon ─────────────── */
const spinStyle = document.createElement('style');
spinStyle.textContent = `@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}`;
document.head.appendChild(spinStyle);

/* ── Boot: load products ─────────────────────────────────── */
fetchProducts();
</script>
@endsection
