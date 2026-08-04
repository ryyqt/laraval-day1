<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a paginated list of invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'items'])
            ->withCount('items')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $invoices  = $query->paginate(10)->withQueryString();
        $statusList = Invoice::statusList();

        return view('invoices.index', compact('invoices', 'statusList'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        $statuses  = Invoice::statusList();

        return view('invoices.create', compact('customers', 'products', 'statuses'));
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'status'      => ['required', 'in:draft,sent,paid,cancelled'],
            'tax_rate'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'due_date'    => ['nullable', 'date'],
            'notes'       => ['nullable', 'string', 'max:1000'],
            'items'       => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['required', 'exists:products,id'],
            'items.*.quantity'    => ['required', 'integer', 'min:1'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
        ], [
            'customer_id.required' => 'Please select a customer.',
            'items.required'       => 'Please add at least one product line item.',
            'items.min'            => 'Please add at least one product line item.',
            'items.*.product_id.required' => 'Each line item needs a product.',
            'items.*.quantity.required'   => 'Each line item needs a quantity.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',
            'items.*.unit_price.required' => 'Each line item needs a unit price.',
        ]);

        $invoice = Invoice::create([
            'customer_id' => $validated['customer_id'],
            'status'      => $validated['status'],
            'tax_rate'    => $validated['tax_rate'] ?? 0,
            'due_date'    => $validated['due_date'] ?? null,
            'notes'       => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $subtotal = round($item['quantity'] * $item['unit_price'], 2);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal'   => $subtotal,
            ]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} created successfully.");
    }

    /**
     * Display a single invoice (printable view).
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'items.product');

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the edit form for an invoice.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load('items.product');
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        $statuses  = Invoice::statusList();

        return view('invoices.edit', compact('invoice', 'customers', 'products', 'statuses'));
    }

    /**
     * Update an existing invoice.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'status'      => ['required', 'in:draft,sent,paid,cancelled'],
            'tax_rate'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'due_date'    => ['nullable', 'date'],
            'notes'       => ['nullable', 'string', 'max:1000'],
            'items'       => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['required', 'exists:products,id'],
            'items.*.quantity'    => ['required', 'integer', 'min:1'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
        ]);

        $invoice->update([
            'customer_id' => $validated['customer_id'],
            'status'      => $validated['status'],
            'tax_rate'    => $validated['tax_rate'] ?? 0,
            'due_date'    => $validated['due_date'] ?? null,
            'notes'       => $validated['notes'] ?? null,
        ]);

        // Replace all items
        $invoice->items()->delete();
        foreach ($validated['items'] as $item) {
            $subtotal = round($item['quantity'] * $item['unit_price'], 2);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal'   => $subtotal,
            ]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} updated successfully.");
    }

    /**
     * Delete an invoice and all its items.
     */
    public function destroy(Invoice $invoice)
    {
        $number = $invoice->invoice_number;
        $invoice->delete(); // cascade deletes items

        return redirect()->route('invoices.index')
            ->with('success', "Invoice {$number} deleted successfully.");
    }
}
