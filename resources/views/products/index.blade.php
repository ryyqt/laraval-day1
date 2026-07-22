@extends('layouts.app')

@section('title', 'Products')
@section('meta_description', 'Manage your products and inventory.')

@section('styles')
<style>
    .page-header {
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 14px;
    }
    .page-header h1 {
        font-size: 1.7rem; font-weight: 800; letter-spacing: -0.03em; color: var(--text-primary);
    }
    .page-header p { margin-top: 4px; color: var(--text-secondary); font-size: 0.9rem; }

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

    .table-card {
        background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden;
    }
    .table-toolbar {
        display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px;
    }
    .table-count { font-size: 0.8rem; color: var(--text-muted); }
    .table-count strong { color: var(--text-secondary); }

    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--bg-elevated); }
    thead th {
        padding: 12px 20px; text-align: left; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border);
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
        display: inline-flex; padding: 4px 10px; border-radius: 999px; background: var(--accent-soft); color: var(--accent-1); font-size: 0.75rem; font-weight: 700;
    }
    .actions { display: flex; align-items: center; gap: 8px; }
    .dt { font-size: 0.78rem; color: var(--text-muted); }

    .empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; gap: 14px; color: var(--text-muted); background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg);
    }
    .empty-icon {
        width: 64px; height: 64px; border-radius: 50%; background: var(--accent-soft); display: flex; align-items: center; justify-content: center;
    }
    .empty-icon svg { width: 28px; height: 28px; color: var(--accent-1); }
    .empty-state h3 { font-size: 1.05rem; font-weight: 700; color: var(--text-secondary); }
    .empty-state p { font-size: 0.875rem; }
</style>
@endsection

@section('content')
<div class="page-shell">
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

    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @isset($products)
        @if($products->count())
            <div class="table-card">
                <div class="table-toolbar">
                    <div class="table-count">
                        Showing <strong>{{ $products->count() }}</strong> products
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
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
                                <td>{{ $product->quantity ?? 0 }}</td>
                                <td class="dt">{{ $product->created_at?->format('M d, Y') ?? '—' }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost btn-sm">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4m-2-4v8"/>
                    </svg>
                </div>
                <h3>No products yet</h3>
                <p>Create your first product to start building the catalog.</p>
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4m-2-4v8"/>
                </svg>
            </div>
            <h3>No products yet</h3>
            <p>Create your first product to start building the catalog.</p>
        </div>
    @endisset
</div>
@endsection