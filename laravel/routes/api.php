<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderManagementController;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::post('/login', [AuthController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {

	// Logout route
	Route::post('/logout', [AuthController::class, 'logout']);

	// Products routes
	Route::get('/products', [ProductController::class, 'index']);
	Route::get('/products/{product}', [ProductController::class, 'show']);
	Route::post('/products', [ProductController::class, 'store']);
	Route::match(['put', 'patch'], '/products/{product}', [ProductController::class, 'update']);
	Route::delete('/products/{product}', [ProductController::class, 'destroy']);

	// Cart routes
	Route::get('/cart', [CartController::class, 'index']); // fetch cart
	Route::post('/cart/items', [CartController::class, 'store']); // add/increment
	Route::put('/cart/items/{item}', [CartController::class, 'update']); // set qty
	Route::delete('/cart/items/{item}', [CartController::class, 'destroy']);// remove
	Route::delete('/cart', [CartController::class, 'clear']); // clear cart

	// Checkout routes
	Route::post('/checkout', [CheckoutController::class, 'checkout']);
	Route::get('/myorders', [CheckoutController::class, 'myOrders']);

	// Admin Order management routes
	Route::get('/orders/grouped-by-customer', [OrderManagementController::class, 'groupedByCustomer']);
	Route::post('/orders/{order}/approve', [OrderManagementController::class, 'approve'])->name('orders.approve');
	Route::post('/orders/{order}/reject', [OrderManagementController::class, 'reject'])->name('orders.reject');

	// Customer update order (only if pending)
	Route::match(['put', 'patch'], '/orders/{order}', [OrderManagementController::class, 'update'])->name('orders.update');


});
