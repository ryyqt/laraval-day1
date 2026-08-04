@extends('layouts.app')

@section('title', 'Invoices')
@section('meta_description', 'Manage all invoices – create, view, edit and delete.')

@section('styles')
<style>
    /* ── Page Header ── */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 28px; flex-wrap: wrap; gap: 14px;
    }
    .page-header h1 { font-size: 1.7rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text-primary); }
    .page-header p  { margin-top: 4px; color: var(--text-secondary); font-size: 0.9rem; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
        border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 600;
        cursor: pointer; transition: var(--transition); border: none; text-decoration: none; white-space: nowrap;
    }
    .btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-primary { background: var(--accent-grad); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.35); }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-ghost   { background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border); }
    .btn-ghost:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .btn-danger  { background: rgba(244,63,94,0.12); color: #f43f5e; border: 1px solid rgba(244,63,94,0.25); }
    .btn-danger:hover { background: rgba(244,63,94,0.22); border-color: rgba(244,63,94,0.5); }
    .btn-sm { padding: 6px 13px; font-size: 0.8rem; }

    /* ── Filter bar ── */
    .filter-bar {
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        padding: 14px 18px; background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius-lg); margin-bottom: 20px;
    }
    .filter-bar .input-wrap { flex: 1; min-width: 200px; position: relative; }
    .filter-bar .input-wrap svg {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        width: 15px; height: 15px; color: var(--text-muted); pointer-events: none;
    }
    .filter-bar input[type="text"] {
        width: 100%; padding: 8px 12px 8px 34px;
        background: var(--bg-elevated); border: 1px solid var(--border);
        border-radius: var(--radius-sm); color: var(--text-primary);
        font-size: 0.875rem; outline: none; transition: var(--transition);
    }
    .filter-bar input[type="text"]:focus { border-color: var(--accent-1); box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
    .status-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .status-tab {
        padding: 7px 14px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600;
        text-decoration: none; transition: var(--transition); border: 1px solid var(--border);
        color: var(--text-secondary); background: var(--bg-elevated);
    }
    .status-tab:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .status-tab.active { background: var(--accent-soft); color: var(--accent-1); border-color: rgba(99,102,241,0.35); }

    /* ── Table card ── */
    .table-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
    .table-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px;
    }
    .table-count { font-size: 0.8rem; color: var(--text-muted); }
    .table-count strong { color: var(--text-secondary); }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--bg-elevated); }
    thead th {
        padding: 11px 18px; text-align: left; font-size: 0.72rem; font-weight: 700;
        letter-spacing: 0.06em; text-transform: uppercase; color: var(--text-muted);
        border-bottom: 1px solid var(--border);
    }
    tbody tr { border-bottom: 1px solid var(--border); transition: background var(--transition); }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--bg-elevated); }
    td { padding: 13px 18px; font-size: 0.875rem; color: var(--text-secondary); vertical-align: middle; }

    .inv-num { font-weight: 700; color: var(--text-primary); font-size: 0.9rem; }
    .dt { font-size: 0.78rem; color: var(--text-muted); }
    .actions { display: flex; align-items: center; gap: 8px; }

    /* Status badges */
    .badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 700;
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
    .badge-draft    { background: rgba(245,158,11,.12); color: #f59e0b; }
    .badge-draft .badge-dot    { background: #f59e0b; }
    .badge-sent     { background: rgba(6,182,212,.12);  color: #06b6d4; }
    .badge-sent .badge-dot     { background: #06b6d4; }
    .badge-paid     { background: rgba(16,185,129,.12); color: #10b981; }
    .badge-paid .badge-dot     { background: #10b981; }
    .badge-cancelled{ background: rgba(244,63,94,.12);  color: #f43f5e; }
    .badge-cancelled .badge-dot{ background: #f43f5e; }

    /* Pagination */
    .pagination-wrap {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 12px;
    }
    .pagination-info { font-size: 0.8rem; color: var(--text-muted); }
    .pagination-links { display: flex; gap: 4px; align-items: center; }
    .pagination-links a, .pagination-links span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 34px; height: 34px; padding: 0 8px; border-radius: var(--radius-sm);
        font-size: 0.82rem; font-weight: 600; text-decoration: none; transition: var(--transition);
    }
    .pagination-links a { background: var(--bg-elevated); border: 1px solid var(--border); color: var(--text-secondary); }
    .pagination-links a:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .pagination-links span.current { background: var(--accent-grad); color: #fff; border: 1px solid transparent; }
    .pagination-links span.disabled { background: var(--bg-elevated); border: 1px solid var(--border); color: var(--text-muted); opacity: .5; }

    /* Empty state */
    .empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 60px 20px; gap: 14px; color: var(--text-muted);
        background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
    }
    .empty-icon { width: 64px; height: 64px; border-radius: 50%; background: var(--accent-soft); display: flex; align-items: center; justify-content: center; }
    .empty-icon svg { width: 28px; height: 28px; color: var(--accent-1); }
    .empty-state h3 { font-size: 1.05rem; font-weight: 700; color: var(--text-secondary); }
    .empty-state p  { font-size: 0.875rem; }
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1>Invoices</h1>
        <p>Create and manage customer invoices with line items.</p>
    </div>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary" id="btn-create-invoice">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        New Invoice
    </a>
</div>

@include('partials.flash')

{{-- Filters --}}
<form method="GET" action="{{ route('invoices.index') }}" id="filter-form">
    <div class="filter-bar">
        <div class="input-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice # or customer…" autocomplete="off">
        </div>
        <div class="status-tabs">
            <a href="{{ route('invoices.index', request()->except(['status','page'])) }}"
               class="status-tab {{ !request('status') ? 'active' : '' }}">All</a>
            @foreach($statusList as $s)
                <a href="{{ route('invoices.index', array_merge(request()->except('page'), ['status' => $s])) }}"
                   class="status-tab {{ request('status') === $s ? 'active' : '' }}"
                   style="text-transform:capitalize">{{ ucfirst($s) }}</a>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        @if(request('search') || request('status'))
            <a href="{{ route('invoices.index') }}" class="btn btn-ghost btn-sm">Clear</a>
        @endif
    </div>
</form>

{{-- Table --}}
@if($invoices->count())
<div class="table-card">
    <div class="table-toolbar">
        <span class="table-count">
            Showing <strong>{{ $invoices->firstItem() }}–{{ $invoices->lastItem() }}</strong>
            of <strong>{{ $invoices->total() }}</strong> invoices
        </span>
    </div>

    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td><span class="inv-num">{{ $invoice->invoice_number }}</span></td>
                    <td style="color:var(--text-primary);font-weight:500">{{ $invoice->customer->name }}</td>
                    <td>
                        <span class="badge badge-{{ $invoice->status }}">
                            <span class="badge-dot"></span>
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td>{{ $invoice->items_count }} item{{ $invoice->items_count !== 1 ? 's' : '' }}</td>
                    <td style="font-weight:600;color:var(--text-primary)">${{ number_format($invoice->total, 2) }}</td>
                    <td class="dt">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '—' }}</td>
                    <td class="dt">{{ $invoice->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-ghost btn-sm"
                               id="btn-view-{{ $invoice->id }}" title="View invoice">View</a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-ghost btn-sm"
                               id="btn-edit-inv-{{ $invoice->id }}" title="Edit invoice">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm"
                                    id="btn-del-inv-{{ $invoice->id }}"
                                    onclick="openDeleteModal(
                                        '{{ route('invoices.destroy', $invoice) }}',
                                        '{{ addslashes($invoice->invoice_number) }}'
                                    )">Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($invoices->hasPages())
    <div class="pagination-wrap">
        <div class="pagination-info">Page {{ $invoices->currentPage() }} of {{ $invoices->lastPage() }}</div>
        <div class="pagination-links">
            @if($invoices->onFirstPage())
                <span class="disabled">‹</span>
            @else
                <a href="{{ $invoices->previousPageUrl() }}" rel="prev">‹</a>
            @endif
            @foreach($invoices->getUrlRange(1, $invoices->lastPage()) as $page => $url)
                @if($page == $invoices->currentPage())
                    <span class="current">{{ $page }}</span>
                @elseif($page == 1 || $page == $invoices->lastPage() || abs($page - $invoices->currentPage()) <= 2)
                    <a href="{{ $url }}">{{ $page }}</a>
                @elseif(abs($page - $invoices->currentPage()) == 3)
                    <span style="color:var(--text-muted);padding:0 4px">…</span>
                @endif
            @endforeach
            @if($invoices->hasMorePages())
                <a href="{{ $invoices->nextPageUrl() }}" rel="next">›</a>
            @else
                <span class="disabled">›</span>
            @endif
        </div>
    </div>
    @endif
</div>

@else
<div class="empty-state">
    <div class="empty-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
        </svg>
    </div>
    @if(request('search') || request('status'))
        <h3>No invoices found</h3>
        <p>Try different search terms or <a href="{{ route('invoices.index') }}" style="color:var(--accent-1)">clear filters</a>.</p>
    @else
        <h3>No invoices yet</h3>
        <p>Create your first invoice to start billing customers.</p>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary" style="margin-top:4px">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Invoice
        </a>
    @endif
</div>
@endif

@include('partials.delete-modal')
@endsection
