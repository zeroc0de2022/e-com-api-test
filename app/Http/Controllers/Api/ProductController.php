<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->has('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            }
            elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        }
        return response()->json($query->get());
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
        return response()->json($product);
    }
}

