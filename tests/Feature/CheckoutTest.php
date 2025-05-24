<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckoutReceivePaymentUrl()
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name'        => 'Product',
            'description' => 'Test',
            'price'       => 50,
        ]);

        $payment = PaymentMethod::create([
            'name'        => 'MockPay',
            'payment_url' => '/payments/mock/{order_id}',
        ]);

        // создаём корзину с товаром
        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
            'quantity'   => 2,
        ]);

        $response = $this->actingAs($user)->postJson('/api/checkout', [
            'payment_method_id' => $payment->id,
        ]);

        $response->dump();
        $response->assertStatus(200)->assertJsonStructure(['message', 'payment_url'])->assertJsonFragment(['message' => 'Order created']);


    }
}

