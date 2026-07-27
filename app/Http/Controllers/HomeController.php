<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function dashboard()
    {
        // Summary stats
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalQuantity   = Product::sum('quantity');

        // Latest 5 products (with category eager-loaded)
        $latestProducts  = Product::with('category')
                                  ->latest()
                                  ->take(5)
                                  ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalQuantity',
            'latestProducts'
        ));
    }
}
