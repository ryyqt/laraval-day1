<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by product name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Sort: latest (default) or oldest
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        // Paginate — 10 per page, keep query string in links
        $products = $query->paginate(10)->withQueryString();

        // All categories for the filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // not needed for this module
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request);

        // Handle image update — delete old image first
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Allow explicitly removing the image
        if ($request->boolean('remove_image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete product image from disk before removing the record
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    protected function validateProduct(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ], [
            'category_id.exists'  => 'Please select a valid category.',
            'name.required'       => 'The product name is required.',
            'name.string'         => 'The product name must be text.',
            'name.max'            => 'The product name must not exceed 255 characters.',
            'price.required'      => 'The price is required.',
            'price.numeric'       => 'The price must be a valid number.',
            'price.min'           => 'The price must be 0 or greater.',
            'quantity.required'   => 'The quantity is required.',
            'quantity.integer'    => 'The quantity must be a whole number.',
            'quantity.min'        => 'The quantity must be 0 or greater.',
            'description.max'     => 'The description must not exceed 1000 characters.',
            'image.image'         => 'The file must be an image.',
            'image.mimes'         => 'Accepted formats: jpg, jpeg, png, webp, gif.',
            'image.max'           => 'Image must be smaller than 2 MB.',
        ]);
    }
}
