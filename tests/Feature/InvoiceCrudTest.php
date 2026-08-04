<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_crud_flow(): void
    {
        $customer = Customer::create([
            'name' => 'Acme Co',
            'email' => 'billing@acme.test',
            'phone_number' => '123-456-7890',
        ]);

        $product = Product::create([
            'category_id' => null,
            'name' => 'Widget',
            'price' => 12.50,
            'quantity' => 100,
            'description' => 'Example widget',
        ]);

        $response = $this->post('/invoices', [
            'customer_id' => $customer->id,
            'status' => 'sent',
            'tax_rate' => 5,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'notes' => 'Payment due within 7 days.',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 12.50,
                ],
            ],
        ]);

        $invoice = Invoice::first();

        $response->assertRedirect(route('invoices.show', $invoice));
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'customer_id' => $customer->id,
            'status' => 'sent',
            'tax_rate' => 5.00,
        ]);
        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 12.50,
            'subtotal' => 25.00,
        ]);

        $this->get('/invoices')->assertOk()->assertSee($invoice->invoice_number);
        $this->get('/invoices/' . $invoice->id . '/edit')->assertOk();

        $updateResponse = $this->put('/invoices/' . $invoice->id, [
            'customer_id' => $customer->id,
            'status' => 'paid',
            'tax_rate' => 10,
            'due_date' => now()->addDays(14)->format('Y-m-d'),
            'notes' => 'Updated payment terms.',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'unit_price' => 12.50,
                ],
            ],
        ]);

        $updateResponse->assertRedirect(route('invoices.show', $invoice));
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id, 'status' => 'paid', 'tax_rate' => 10.00]);
        $this->assertDatabaseHas('invoice_items', ['invoice_id' => $invoice->id, 'quantity' => 3, 'subtotal' => 37.50]);

        $deleteResponse = $this->delete('/invoices/' . $invoice->id);
        $deleteResponse->assertRedirect('/invoices');
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
        $this->assertDatabaseMissing('invoice_items', ['invoice_id' => $invoice->id]);
    }

    public function test_invoice_validation_rejects_invalid_data(): void
    {
        $response = $this->post('/invoices', [
            'customer_id' => 999,
            'status' => 'unknown',
            'tax_rate' => -10,
            'items' => [],
        ]);

        $response->assertSessionHasErrors(['customer_id', 'status', 'tax_rate', 'items']);
        $this->assertDatabaseCount('invoices', 0);
    }
}
