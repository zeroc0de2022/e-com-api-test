<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function testAddProductToCart()
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name'        => 'Test Product',
            'description' => 'Description',
            'price'       => 99.99,
        ]);

        $response = $this->actingAs($user)->postJson('/api/cart/items', [
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Item added to cart']);
        $response->dump();

    }
}
