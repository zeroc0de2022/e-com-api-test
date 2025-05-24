<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentCallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
});

Route::middleware('auth:sanctum')->prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/items', 'addItem');
    Route::delete('/items/{id}', 'removeItem');
});

Route::middleware('auth:sanctum')->post('/checkout', [CheckoutController::class, 'checkout']);
Route::get('/payments/mock/{order}', [PaymentCallbackController::class, 'markAsPaid']);
