<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of all customers.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:customers,email',
            'phone_number' => 'required|string|max:20',
        ], [
            'name.required'         => 'The customer name is required.',
            'name.max'              => 'The customer name must not exceed 255 characters.',
            'email.required'        => 'The email address is required.',
            'email.email'           => 'Please provide a valid email address.',
            'email.unique'          => 'A customer with this email already exists.',
            'phone_number.required' => 'The phone number is required.',
            'phone_number.max'      => 'The phone number must not exceed 20 characters.',
        ]);

        Customer::create($request->only(['name', 'email', 'phone_number']));

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone_number' => 'required|string|max:20',
        ], [
            'name.required'         => 'The customer name is required.',
            'name.max'              => 'The customer name must not exceed 255 characters.',
            'email.required'        => 'The email address is required.',
            'email.email'           => 'Please provide a valid email address.',
            'email.unique'          => 'A customer with this email already exists.',
            'phone_number.required' => 'The phone number is required.',
            'phone_number.max'      => 'The phone number must not exceed 20 characters.',
        ]);

        $customer->update($request->only(['name', 'email', 'phone_number']));

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
