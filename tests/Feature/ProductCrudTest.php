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
}
