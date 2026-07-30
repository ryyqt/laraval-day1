<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class AjaxProductPageController extends Controller
{
    /**
     * Render the Day-9 AJAX products page.
     */
    public function index(): View
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('ajax-products.index', compact('categories'));
    }
}
