@extends('layouts.app')

@section('title', 'Categories')
@section('meta_description', 'Manage all categories – create, edit and delete categories.')

@section('styles')
<style>
    /* ── Page-level styles for Categories Index ── */
    .cat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 14px;
    }
    .cat-header-left h1 {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text-primary);
    }
    .cat-header-left p {
        margin-top: 4px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    /* ── Btn ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        border: none;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-primary {
        background: var(--accent-grad);
        color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99,102,241,0.45);
    }
    .btn-ghost {
        background: var(--bg-elevated);
        color: var(--text-secondary);
        border: 1px solid var(--border);
    }
    .btn-ghost:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .btn-danger {
        background: rgba(244,63,94,0.12);
        color: #f43f5e;
        border: 1px solid rgba(244,63,94,0.25);
    }
    .btn-danger:hover { background: rgba(244,63,94,0.22); border-color: rgba(244,63,94,0.5); }
    .btn-sm { padding: 6px 13px; font-size: 0.8rem; }

    /* ── Alert ── */
    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 22px;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .alert svg { width: 18px; height: 18px; flex-shrink: 0; }
    .alert-success {
        background: rgba(16,185,129,0.12);
        border: 1px solid rgba(16,185,129,0.3);
        color: #10b981;
    }
    .alert-error {
        background: rgba(244,63,94,0.12);
        border: 1px solid rgba(244,63,94,0.3);
        color: #f43f5e;
    }

    /* ── Table card ── */
    .table-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 10px;
    }
    .table-count {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    .table-count strong { color: var(--text-secondary); }

    /* Search */
    .search-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .search-wrap svg {
        position: absolute;
        left: 11px;
        width: 15px; height: 15px;
        color: var(--text-muted);
        pointer-events: none;
    }
    #search-input {
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        font-size: 0.85rem;
        padding: 7px 12px 7px 34px;
        outline: none;
        width: 220px;
        transition: var(--transition);
    }
    #search-input::placeholder { color: var(--text-muted); }
    #search-input:focus { border-color: var(--border-hover); background: var(--bg-elevated); }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--bg-elevated); }
    thead th {
        padding: 12px 20px;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
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
        padding: 14px 20px;
        font-size: 0.875rem;
        color: var(--text-secondary);
        vertical-align: middle;
    }

    /* Category name pill */
    .cat-name {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
        font-weight: 600;
    }
    .cat-dot {
        width: 8px; height: 8px;
        border-radius: 999px;
        background: var(--accent-grad);
        flex-shrink: 0;
    }
    .cat-desc {
        max-width: 340px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--text-muted);
        font-size: 0.83rem;
    }
    .cat-desc-empty { color: var(--text-muted); font-style: italic; font-size: 0.83rem; }

    .actions { display: flex; align-items: center; gap: 8px; }

    /* Badge / ID */
    .id-badge {
        display: inline-block;
        padding: 2px 8px;
        background: var(--accent-soft);
        color: var(--accent-1);
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    /* Datetime */
    .dt { font-size: 0.78rem; color: var(--text-muted); }

    /* Empty state */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        gap: 14px;
        color: var(--text-muted);
    }
    .empty-icon {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
    }
    .empty-icon svg { width: 28px; height: 28px; color: var(--accent-1); }
    .empty-state h3 { font-size: 1.05rem; font-weight: 700; color: var(--text-secondary); }
    .empty-state p  { font-size: 0.875rem; }

    /* Pagination */
    .pagination-wrap {
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .pagination-wrap .pagination {
        display: flex;
        gap: 4px;
        list-style: none;
    }
    .pagination-wrap .page-item .page-link {
        display: flex; align-items: center; justify-content: center;
        width: 34px; height: 34px;
        border-radius: var(--radius-sm);
        font-size: 0.82rem; font-weight: 600;
        color: var(--text-secondary);
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        transition: var(--transition);
    }
    .pagination-wrap .page-item.active .page-link,
    .pagination-wrap .page-item .page-link:hover {
        background: var(--accent-soft);
        border-color: var(--border-hover);
        color: var(--accent-1);
    }
    .pagination-wrap .page-item.disabled .page-link {
        opacity: 0.4;
        pointer-events: none;
    }

    /* Delete modal */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 32px;
        max-width: 420px;
        width: 90%;
        animation: modalIn 0.25s ease;
        position: relative;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.93) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-icon {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: rgba(244,63,94,0.12);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 16px;
    }
    .modal-icon svg { width: 24px; height: 24px; color: #f43f5e; }
    .modal h3 { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
    .modal p  { font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 24px; }
    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="cat-header">
    <div class="cat-header-left">
        <h1>Categories</h1>
        <p>Manage your category taxonomy – create, update, and remove entries.</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary" id="btn-add-category">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Add Category
    </a>
</div>

<!-- Flash Messages -->
@if(session('success'))
<div class="alert alert-success" id="flash-success" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-error" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
    </svg>
    {{ session('error') }}
</div>
@endif

<!-- Table Card -->
<div class="table-card">
    <!-- Toolbar -->
    <div class="table-toolbar">
        <span class="table-count">
            Showing <strong>{{ $categories->count() }}</strong> of <strong>{{ $categories->total() }}</strong> categories
        </span>
        <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input type="text" id="search-input" placeholder="Filter categories…" aria-label="Search categories">
        </div>
    </div>

    @if($categories->isEmpty())
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
                </svg>
            </div>
            <h3>No categories yet</h3>
            <p>Get started by creating your first category.</p>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Category
            </a>
        </div>
    @else
        <!-- Table -->
        <div style="overflow-x:auto;">
            <table id="categories-table" aria-label="Categories table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr id="row-{{ $category->id }}" data-name="{{ strtolower($category->name) }} {{ strtolower($category->description ?? '') }}">
                        <td><span class="id-badge">#{{ $category->id }}</span></td>
                        <td>
                            <span class="cat-name">
                                <span class="cat-dot"></span>
                                {{ $category->name }}
                            </span>
                        </td>
                        <td>
                            @if($category->description)
                                <span class="cat-desc" title="{{ $category->description }}">{{ $category->description }}</span>
                            @else
                                <span class="cat-desc-empty">No description</span>
                            @endif
                        </td>
                        <td><span class="dt">{{ $category->created_at->format('M d, Y') }}</span></td>
                        <td>
                            <div class="actions" style="justify-content:flex-end;">
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="btn btn-ghost btn-sm"
                                   id="btn-edit-{{ $category->id }}"
                                   title="Edit category">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                                    </svg>
                                    Edit
                                </a>
                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        id="btn-delete-{{ $category->id }}"
                                        title="Delete category"
                                        onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="pagination-wrap">
            {{ $categories->links() }}
        </div>
        @endif
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="delete-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal">
        <div class="modal-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
        </div>
        <h3 id="modal-title">Delete Category</h3>
        <p id="modal-desc">Are you sure you want to delete <strong id="modal-name"></strong>? This action cannot be undone.</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-ghost" id="btn-cancel-delete" onclick="closeDeleteModal()">Cancel</button>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" id="btn-confirm-delete">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ── Live search filter ──────────────────────────────────────
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('#categories-table tbody tr').forEach(row => {
                const text = row.dataset.name || '';
                row.style.display = (!q || text.includes(q)) ? '' : 'none';
            });
        });
    }

    // ── Delete modal ────────────────────────────────────────────
    function openDeleteModal(id, name) {
        document.getElementById('modal-name').textContent = name;
        document.getElementById('delete-form').action = '/categories/' + id;
        document.getElementById('delete-modal').classList.add('open');
        document.getElementById('btn-confirm-delete').focus();
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.remove('open');
    }

    // Close on overlay click
    document.getElementById('delete-modal').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeDeleteModal();
    });

    // Auto-dismiss flash message
    const flash = document.getElementById('flash-success');
    if (flash) setTimeout(() => flash.style.display = 'none', 4000);
</script>
@endsection
