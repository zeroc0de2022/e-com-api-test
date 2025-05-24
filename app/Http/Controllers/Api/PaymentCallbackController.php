<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class PaymentCallbackController extends Controller
{
    public function markAsPaid($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order already processed'], 400);
        }
        $order->update(['status' => 'paid']);
        return response()->json(['message' => 'Order marked as paid']);
    }
}

