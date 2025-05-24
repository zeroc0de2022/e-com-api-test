<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;


class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testPaymentCallbackOrder()
    {
        $user = User::factory()->create();

        $paymentMethod = PaymentMethod::create([
            'name'        => 'MockPay',
            'payment_url' => '/payments/mock/{order_id}',
        ]);

        $order = Order::create([
            'user_id'           => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'status'            => 'pending',
        ]);

        $response = $this->getJson("/api/payments/mock/{$order->id}");
        $response->assertStatus(200)->assertJson(['message' => 'Order marked as paid']);
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'paid',
        ]);
    }
}

