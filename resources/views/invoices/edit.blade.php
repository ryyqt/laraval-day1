@extends('layouts.app')

@section('title', 'Edit ' . $invoice->invoice_number)
@section('meta_description', 'Edit invoice ' . $invoice->invoice_number)

@section('styles')
{{-- Reuse the same styles as create --}}
<style>
    .form-page-wrap { max-width: 900px; margin: 0 auto; }
    .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 0.82rem; color: var(--text-muted); margin-bottom: 24px; }
    .breadcrumb a { color: var(--text-muted); transition: color var(--transition); }
    .breadcrumb a:hover { color: var(--accent-1); }
    .breadcrumb svg { width: 13px; height: 13px; }
    .breadcrumb-current { color: var(--text-secondary); font-weight: 500; }

    .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 20px; }
    .form-card-header { padding: 22px 28px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 14px; }
    .form-card-icon { width: 42px; height: 42px; border-radius: var(--radius-sm); background: var(--accent-soft); display: flex; align-items: center; justify-content: center; }
    .form-card-icon svg { width: 20px; height: 20px; color: var(--accent-1); }
    .form-card-header h2 { font-size: 1.05rem; font-weight: 700; color: var(--text-primary); }
    .form-card-header p  { font-size: 0.83rem; color: var(--text-secondary); margin-top: 2px; }
    .form-body { padding: 28px; display: flex; flex-direction: column; gap: 20px; }

    .field-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    @media (max-width: 700px) { .field-row-2 { grid-template-columns: 1fr; } }
    .field-group { display: flex; flex-direction: column; gap: 6px; }
    label { font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); display: flex; align-items: center; gap: 4px; }
    label .req { color: #f43f5e; }
    .input-wrap { position: relative; }
    .input-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: var(--text-muted); pointer-events: none; }

    input[type="text"], input[type="number"], input[type="date"], select, textarea {
        width: 100%; background: var(--bg-elevated); border: 1px solid var(--border);
        border-radius: var(--radius-sm); color: var(--text-primary); font-family: inherit;
        font-size: 0.9rem; padding: 10px 14px; outline: none; transition: var(--transition);
    }
    input.has-icon, select.has-icon { padding-left: 36px; }
    textarea { resize: vertical; min-height: 90px; }
    input:focus, select:focus, textarea:focus { border-color: var(--border-hover); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
    input.is-invalid, select.is-invalid, textarea.is-invalid { border-color: rgba(244,63,94,.6); box-shadow: 0 0 0 3px rgba(244,63,94,.1); }
    .field-error { font-size: 0.78rem; color: #f43f5e; display: flex; align-items: center; gap: 4px; margin-top: 2px; }

    .items-table-wrap { overflow-x: auto; }
    #line-items-table { width: 100%; border-collapse: collapse; }
    #line-items-table th { padding: 10px 14px; text-align: left; font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); background: var(--bg-elevated); border-bottom: 1px solid var(--border); }
    #line-items-table td { padding: 10px 10px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    #line-items-table tbody tr:last-child td { border-bottom: none; }

    .item-select, .item-input { width: 100%; background: var(--bg-base); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text-primary); font-family: inherit; font-size: .85rem; padding: 8px 10px; outline: none; transition: var(--transition); }
    .item-select:focus, .item-input:focus { border-color: var(--accent-1); box-shadow: 0 0 0 2px rgba(99,102,241,.12); }
    .item-subtotal { font-weight: 600; color: var(--text-primary); font-size: .9rem; min-width: 90px; }

    .btn-remove-row { background: rgba(244,63,94,.1); border: 1px solid rgba(244,63,94,.2); color: #f43f5e; border-radius: var(--radius-sm); padding: 6px 10px; cursor: pointer; font-size: .8rem; font-weight: 600; transition: var(--transition); display: inline-flex; align-items: center; gap: 4px; }
    .btn-remove-row:hover { background: rgba(244,63,94,.2); }
    .btn-remove-row svg { width: 14px; height: 14px; }

    .btn-add-row { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: var(--radius-sm); font-size: .875rem; font-weight: 600; cursor: pointer; transition: var(--transition); border: 1px dashed var(--border); color: var(--accent-1); background: var(--accent-soft); text-decoration: none; }
    .btn-add-row:hover { border-color: var(--accent-1); }
    .btn-add-row svg { width: 15px; height: 15px; }

    .totals-box { margin-left: auto; max-width: 340px; background: var(--bg-elevated); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 18px 22px; display: flex; flex-direction: column; gap: 10px; }
    .total-row { display: flex; justify-content: space-between; align-items: center; font-size: .9rem; }
    .total-row .label { color: var(--text-secondary); }
    .total-row .val   { font-weight: 600; color: var(--text-primary); }
    .total-row.grand  { border-top: 1px solid var(--border); padding-top: 10px; margin-top: 2px; }
    .total-row.grand .label { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
    .total-row.grand .val   { font-size: 1.2rem; font-weight: 800; background: var(--accent-grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

    .form-footer { display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding: 20px 28px; border-top: 1px solid var(--border); }
    .btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px; border-radius: var(--radius-sm); font-size: .9rem; font-weight: 600; cursor: pointer; transition: var(--transition); border: none; text-decoration: none; }
    .btn svg { width: 16px; height: 16px; }
    .btn-primary { background: var(--accent-grad); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,.35); }
    .btn-primary:hover { opacity: .9; transform: translateY(-1px); }
    .btn-ghost { background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border); }
    .btn-ghost:hover { border-color: var(--border-hover); color: var(--text-primary); }
</style>
@endsection

@section('content')
<div class="form-page-wrap">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('invoices.index') }}">Invoices</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="breadcrumb-current">Edit</span>
    </nav>

    <form method="POST" action="{{ route('invoices.update', $invoice) }}" id="invoice-form">
        @csrf
        @method('PUT')

        {{-- ── Section 1: Invoice Details ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                </div>
                <div>
                    <h2>Edit {{ $invoice->invoice_number }}</h2>
                    <p>Update the invoice details and line items.</p>
                </div>
            </div>
            <div class="form-body">
                <div class="field-row-2">
                    <div class="field-group">
                        <label for="customer_id">Customer <span class="req">*</span></label>
                        <div class="input-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            <select id="customer_id" name="customer_id" class="has-icon {{ $errors->has('customer_id') ? 'is-invalid' : '' }}">
                                <option value="">— Select a customer —</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('customer_id')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="field-group">
                        <label for="status">Status <span class="req">*</span></label>
                        <select id="status" name="status" class="{{ $errors->has('status') ? 'is-invalid' : '' }}">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status', $invoice->status) === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field-row-2">
                    <div class="field-group">
                        <label for="tax_rate">Tax Rate (%)</label>
                        <input type="number" id="tax_rate" name="tax_rate"
                               value="{{ old('tax_rate', $invoice->tax_rate) }}"
                               min="0" max="100" step="0.01"
                               class="{{ $errors->has('tax_rate') ? 'is-invalid' : '' }}"
                               oninput="recalcTotals()">
                        @error('tax_rate')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="field-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" id="due_date" name="due_date"
                               value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}"
                               class="{{ $errors->has('due_date') ? 'is-invalid' : '' }}">
                        @error('due_date')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" class="{{ $errors->has('notes') ? 'is-invalid' : '' }}">{{ old('notes', $invoice->notes) }}</textarea>
                    @error('notes')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- ── Section 2: Line Items ── --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                </div>
                <div>
                    <h2>Line Items</h2>
                    <p>Update the products on this invoice.</p>
                </div>
            </div>

            <div style="padding:24px 28px;display:flex;flex-direction:column;gap:16px">
                @error('items')
                    <div class="field-error" style="font-size:.85rem;padding:8px 14px;background:rgba(244,63,94,.08);border-radius:var(--radius-sm);border:1px solid rgba(244,63,94,.2)">{{ $message }}</div>
                @enderror

                <div class="items-table-wrap">
                    <table id="line-items-table">
                        <thead>
                            <tr>
                                <th style="width:40%">Product</th>
                                <th style="width:12%">Qty</th>
                                <th style="width:18%">Unit Price ($)</th>
                                <th style="width:16%">Subtotal</th>
                                <th style="width:14%"></th>
                            </tr>
                        </thead>
                        <tbody id="line-items-body"></tbody>
                    </table>
                </div>

                <button type="button" class="btn-add-row" onclick="addLineItem()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Line Item
                </button>

                <div class="totals-box">
                    <div class="total-row">
                        <span class="label">Subtotal</span>
                        <span class="val" id="display-subtotal">$0.00</span>
                    </div>
                    <div class="total-row">
                        <span class="label">Tax (<span id="display-tax-rate">0</span>%)</span>
                        <span class="val" id="display-tax">$0.00</span>
                    </div>
                    <div class="total-row grand">
                        <span class="label">Total</span>
                        <span class="val" id="display-total">$0.00</span>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    Update Invoice
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
const PRODUCTS = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float)$p->price]));
@php
    $existingItems = $invoice->items->map(function ($item) {
        return [
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => (float) $item->unit_price,
        ];
    });
@endphp
const EXISTING_ITEMS = @json($existingItems);

let rowIndex = 0;

function buildProductOptions(selectedId) {
    return PRODUCTS.map(p =>
        `<option value="${p.id}" data-price="${p.price}" ${p.id == selectedId ? 'selected' : ''}>${p.name} — $${parseFloat(p.price).toFixed(2)}</option>`
    ).join('');
}

function addLineItem(data = {}) {
    const idx = rowIndex++;
    const tbody = document.getElementById('line-items-body');
    const tr = document.createElement('tr');
    tr.id = `row-${idx}`;
    tr.innerHTML = `
        <td>
            <select class="item-select" name="items[${idx}][product_id]" onchange="onProductChange(this,${idx})" required>
                <option value="">— Select product —</option>
                ${buildProductOptions(data.product_id || '')}
            </select>
        </td>
        <td><input type="number" class="item-input" name="items[${idx}][quantity]" id="qty-${idx}" value="${data.quantity || 1}" min="1" style="width:80px" oninput="recalcRow(${idx})" required></td>
        <td><input type="number" class="item-input" name="items[${idx}][unit_price]" id="price-${idx}" value="${data.unit_price || ''}" min="0" step="0.01" style="width:110px" oninput="recalcRow(${idx})" required></td>
        <td><span class="item-subtotal" id="sub-${idx}">$0.00</span></td>
        <td><button type="button" class="btn-remove-row" onclick="removeRow(${idx})">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            Remove</button></td>
    `;
    tbody.appendChild(tr);
    recalcRow(idx);
}

function onProductChange(sel, idx) {
    const opt = sel.options[sel.selectedIndex];
    const price = opt ? parseFloat(opt.dataset.price || 0) : 0;
    document.getElementById(`price-${idx}`).value = price.toFixed(2);
    recalcRow(idx);
}

function recalcRow(idx) {
    const qty   = parseFloat(document.getElementById(`qty-${idx}`)?.value   || 0);
    const price = parseFloat(document.getElementById(`price-${idx}`)?.value || 0);
    const el    = document.getElementById(`sub-${idx}`);
    if (el) el.textContent = '$' + (qty * price).toFixed(2);
    recalcTotals();
}

function recalcTotals() {
    let subtotal = 0;
    document.querySelectorAll('[id^="sub-"]').forEach(el => {
        subtotal += parseFloat(el.textContent.replace('$','')) || 0;
    });
    const taxRate = parseFloat(document.getElementById('tax_rate')?.value || 0);
    const tax     = subtotal * taxRate / 100;
    document.getElementById('display-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('display-tax').textContent      = '$' + tax.toFixed(2);
    document.getElementById('display-total').textContent    = '$' + (subtotal + tax).toFixed(2);
    document.getElementById('display-tax-rate').textContent = taxRate;
}

function removeRow(idx) {
    document.getElementById(`row-${idx}`)?.remove();
    recalcTotals();
}

document.addEventListener('DOMContentLoaded', function () {
    const oldItems = @json(old('items', []));
    const items = (oldItems && oldItems.length) ? oldItems : EXISTING_ITEMS;
    if (items && items.length) {
        items.forEach(item => addLineItem(item));
    } else {
        addLineItem();
    }
});
</script>
@endsection
