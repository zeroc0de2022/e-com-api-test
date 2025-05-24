<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart()->with('items.product')->first();
        return response()->json($cart);
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);
        $user = $request->user();
        $cart = $user->cart()->firstOrCreate([]);
        $item = $cart->items()->where('product_id', $request->product_id)->first();
        if ($item) {
            $item->increment('quantity', $request->quantity);
        }
        else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }
        return response()->json(['message' => 'Item added to cart']);
    }

    public function removeItem($id, Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $item = $cart->items()->where('id', $id)->first();
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }
}
