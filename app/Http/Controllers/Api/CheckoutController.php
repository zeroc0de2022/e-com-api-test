<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        $paymentMethod = PaymentMethod::find($request->payment_method_id);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'           => $user->id,
                'payment_method_id' => $paymentMethod->id,
                'status'            => 'pending',
            ]);
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }
            // Удалить корзину
            $cart->items()->delete();
            $cart->delete();
            DB::commit();

            // Подставить {order_id} в шаблон ссылки
            $paymentUrl = str_replace('{order_id}', $order->id, $paymentMethod->payment_url_template);
            return response()->json([
                'message' => 'Order created',
                'payment_url' => url($paymentUrl),
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => 'Error during checkout', 'error' => $exception->getMessage()], 500);
        }
    }


}

