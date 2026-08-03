<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;

class HomeController extends Controller
{
    public function dashboard()
    {
        // ── Summary stats ──────────────────────────────────────
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalCustomers  = Customer::count();
        $totalQuantity   = Product::sum('quantity');
        $totalValue      = Product::selectRaw('SUM(price * quantity) as total')->value('total') ?? 0;
        $lowStockCount   = Product::where('quantity', '<=', 5)->count();

        // ── Latest 5 products (with category eager-loaded) ─────
        $latestProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        // ── Low-stock products (qty ≤ 5) ───────────────────────
        $lowStockProducts = Product::with('category')
            ->where('quantity', '<=', 5)
            ->orderBy('quantity')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalCustomers',
            'totalQuantity',
            'totalValue',
            'lowStockCount',
            'latestProducts',
            'lowStockProducts',
        ));
    }
}
