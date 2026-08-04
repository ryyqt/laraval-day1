@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('meta_description', 'View invoice ' . $invoice->invoice_number)

@section('styles')
<style>
    /* ── Back bar ── */
    .back-bar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.82rem; color: var(--text-muted);
    }
    .breadcrumb a { color: var(--text-muted); transition: color var(--transition); }
    .breadcrumb a:hover { color: var(--accent-1); }
    .breadcrumb svg { width: 13px; height: 13px; }
    .breadcrumb-current { color: var(--text-secondary); font-weight: 500; }
    .action-group { display: flex; align-items: center; gap: 10px; }

    /* Buttons */
    .btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: var(--transition); border: none; text-decoration: none; white-space: nowrap; }
    .btn svg { width: 15px; height: 15px; }
    .btn-primary { background: var(--accent-grad); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,.35); }
    .btn-primary:hover { opacity: .9; transform: translateY(-1px); }
    .btn-ghost { background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border); }
    .btn-ghost:hover { border-color: var(--border-hover); color: var(--text-primary); }
    .btn-danger { background: rgba(244,63,94,.12); color: #f43f5e; border: 1px solid rgba(244,63,94,.25); }
    .btn-danger:hover { background: rgba(244,63,94,.22); }

    /* ── Invoice paper ── */
    .invoice-paper {
        max-width: 820px; margin: 0 auto;
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius-lg); overflow: hidden;
    }

    /* Header band */
    .inv-header {
        padding: 36px 40px 28px;
        background: linear-gradient(135deg, rgba(99,102,241,.08) 0%, rgba(139,92,246,.05) 100%);
        border-bottom: 1px solid var(--border);
        display: flex; align-items: flex-start; justify-content: space-between;
        flex-wrap: wrap; gap: 24px;
    }
    .inv-brand { display: flex; align-items: center; gap: 12px; }
    .inv-brand-logo {
        width: 44px; height: 44px; border-radius: 12px; background: var(--accent-grad);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 14px rgba(99,102,241,.4);
    }
    .inv-brand-logo svg { width: 22px; height: 22px; color: #fff; }
    .inv-brand-name { font-size: 1.4rem; font-weight: 800; letter-spacing: -.03em; }
    .inv-brand-name span { background: var(--accent-grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .inv-brand-sub { font-size: .8rem; color: var(--text-muted); margin-top: 2px; }

    .inv-meta { text-align: right; }
    .inv-number { font-size: 1.5rem; font-weight: 800; letter-spacing: -.03em; color: var(--text-primary); }
    .inv-date-row { font-size: .83rem; color: var(--text-secondary); margin-top: 4px; }
    .inv-date-row strong { color: var(--text-primary); }

    /* Status badge */
    .inv-status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 14px; border-radius: 999px; font-size: .78rem; font-weight: 700;
        margin-top: 8px;
    }
    .inv-status-badge .bdot { width: 7px; height: 7px; border-radius: 50%; }
    .status-draft    { background: rgba(245,158,11,.12); color: #f59e0b; }
    .status-draft .bdot    { background: #f59e0b; }
    .status-sent     { background: rgba(6,182,212,.12);  color: #06b6d4; }
    .status-sent .bdot     { background: #06b6d4; }
    .status-paid     { background: rgba(16,185,129,.12); color: #10b981; }
    .status-paid .bdot     { background: #10b981; }
    .status-cancelled{ background: rgba(244,63,94,.12);  color: #f43f5e; }
    .status-cancelled .bdot{ background: #f43f5e; }

    /* Billing info */
    .inv-parties {
        display: grid; grid-template-columns: 1fr 1fr;
        padding: 28px 40px; gap: 24px; border-bottom: 1px solid var(--border);
    }
    @media (max-width: 600px) { .inv-parties { grid-template-columns: 1fr; } }
    .party-block h4 { font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; }
    .party-name  { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
    .party-detail { font-size: .85rem; color: var(--text-secondary); margin-top: 4px; }

    /* Items table */
    .inv-items { padding: 0; }
    .inv-items table { width: 100%; border-collapse: collapse; }
    .inv-items thead th {
        padding: 12px 40px; text-align: left; font-size: .72rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted);
        background: var(--bg-elevated); border-bottom: 1px solid var(--border);
    }
    .inv-items thead th:last-child { text-align: right; }
    .inv-items tbody td { padding: 14px 40px; border-bottom: 1px solid var(--border); font-size: .9rem; color: var(--text-secondary); vertical-align: middle; }
    .inv-items tbody tr:last-child td { border-bottom: none; }
    .inv-items tbody td:last-child { text-align: right; font-weight: 600; color: var(--text-primary); }
    .item-prod-name { font-weight: 600; color: var(--text-primary); }

    /* Totals */
    .inv-totals { padding: 24px 40px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }
    .totals-table { min-width: 280px; display: flex; flex-direction: column; gap: 10px; }
    .t-row { display: flex; justify-content: space-between; align-items: center; font-size: .9rem; }
    .t-row .lbl { color: var(--text-secondary); }
    .t-row .val { font-weight: 600; color: var(--text-primary); }
    .t-row.grand { border-top: 2px solid var(--border); padding-top: 12px; margin-top: 4px; }
    .t-row.grand .lbl { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
    .t-row.grand .val { font-size: 1.25rem; font-weight: 800; background: var(--accent-grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

    /* Notes */
    .inv-notes { padding: 20px 40px; border-top: 1px solid var(--border); }
    .inv-notes h5 { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--text-muted); margin-bottom: 6px; }
    .inv-notes p  { font-size: .875rem; color: var(--text-secondary); line-height: 1.6; }

    /* Footer */
    .inv-footer { padding: 16px 40px; border-top: 1px solid var(--border); text-align: center; font-size: .78rem; color: var(--text-muted); }

    @media print {
        .back-bar, .action-group { display: none !important; }
        body { background: #fff !important; }
        .app-shell { display: block !important; }
        .sidebar, .topbar { display: none !important; }
        .content { padding: 0 !important; }
        .invoice-paper { border: none !important; border-radius: 0 !important; max-width: 100% !important; }
        .inv-header { background: #f8f8ff !important; }
    }
</style>
@endsection

@section('content')

@include('partials.flash')

{{-- Back bar --}}
<div class="back-bar">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('invoices.index') }}">Invoices</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="breadcrumb-current">{{ $invoice->invoice_number }}</span>
    </nav>
    <div class="action-group">
        <button onclick="window.print()" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
            </svg>
            Print
        </button>
        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
            Edit
        </a>
        <button type="button" class="btn btn-danger"
                onclick="openDeleteModal('{{ route('invoices.destroy', $invoice) }}', '{{ addslashes($invoice->invoice_number) }}')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
            Delete
        </button>
    </div>
</div>

{{-- Invoice Paper --}}
<div class="invoice-paper">

    {{-- Header --}}
    <div class="inv-header">
        <div>
            <div class="inv-brand">
                <div class="inv-brand-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div class="inv-brand-name"><span>Nexus</span> IMS</div>
                    <div class="inv-brand-sub">Inventory Management System</div>
                </div>
            </div>
        </div>
        <div class="inv-meta">
            <div class="inv-number">{{ $invoice->invoice_number }}</div>
            <div class="inv-date-row">
                Issued: <strong>{{ $invoice->created_at->format('M d, Y') }}</strong>
                @if($invoice->due_date)
                    &nbsp;·&nbsp; Due: <strong>{{ $invoice->due_date->format('M d, Y') }}</strong>
                @endif
            </div>
            <div>
                <span class="inv-status-badge status-{{ $invoice->status }}">
                    <span class="bdot"></span>
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Bill To --}}
    <div class="inv-parties">
        <div class="party-block">
            <h4>Bill To</h4>
            <div class="party-name">{{ $invoice->customer->name }}</div>
            <div class="party-detail">{{ $invoice->customer->email }}</div>
            <div class="party-detail">{{ $invoice->customer->phone_number }}</div>
        </div>
        <div class="party-block" style="text-align:right">
            <h4>From</h4>
            <div class="party-name">Nexus IMS</div>
            <div class="party-detail">inventory@nexus.app</div>
        </div>
    </div>

    {{-- Line Items --}}
    <div class="inv-items">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $i => $item)
                <tr>
                    <td style="color:var(--text-muted);font-size:.82rem">{{ $i + 1 }}</td>
                    <td><span class="item-prod-name">{{ $item->product->name }}</span></td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Totals --}}
    <div class="inv-totals">
        <div class="totals-table">
            <div class="t-row">
                <span class="lbl">Subtotal</span>
                <span class="val">${{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="t-row">
                <span class="lbl">Tax ({{ $invoice->tax_rate }}%)</span>
                <span class="val">${{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            <div class="t-row grand">
                <span class="lbl">Total</span>
                <span class="val">${{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if($invoice->notes)
    <div class="inv-notes">
        <h5>Notes</h5>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    <div class="inv-footer">
        Thank you for your business! · Generated by Nexus IMS
    </div>
</div>

@include('partials.delete-modal')
@endsection
