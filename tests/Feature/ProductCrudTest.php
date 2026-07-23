<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_crud_flow(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices',
        ]);

        $response = $this->post('/products', [
            'category_id' => $category->id,
            'name' => 'Laptop',
            'price' => 999.99,
            'quantity' => 10,
            'description' => 'Gaming laptop',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'Laptop']);

        $product = Product::where('name', 'Laptop')->firstOrFail();

        $this->get('/products')->assertOk();
        $this->get('/products/' . $product->id . '/edit')->assertOk();

        $updateResponse = $this->put('/products/' . $product->id, [
            'category_id' => $category->id,
            'name' => 'Updated Laptop',
            'price' => 1099.99,
            'quantity' => 7,
            'description' => 'Updated description',
        ]);

        $updateResponse->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'Updated Laptop']);

        $deleteResponse = $this->delete('/products/' . $product->id);
        $deleteResponse->assertRedirect('/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_invalid_product_submission_is_rejected(): void
    {
        $response = $this->post('/products', [
            'category_id' => 999,
            'name' => '',
            'price' => -5,
            'quantity' => -1,
            'description' => str_repeat('x', 1001),
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'quantity', 'category_id', 'description']);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_index_displays_the_category_name(): void
    {
        $category = Category::create([
            'name' => 'Accessories',
            'description' => 'Accessory items',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Mouse',
            'price' => 29.99,
            'quantity' => 5,
            'description' => 'Wireless mouse',
        ]);

        $this->get('/products')
            ->assertOk()
            ->assertSee('Mouse')
            ->assertSee('Accessories');
    }
}
