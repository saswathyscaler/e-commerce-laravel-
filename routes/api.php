<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route:: middleware('auth:api')->group(function(){
    Route::get('/user/{id}', [UserController::class, 'getUser']);
});


Route::post('/addproduct', [ProductController::class, 'store']);

Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{id}', [ProductController::class, 'show']);

Route::post('/products/{id}', [ProductController::class, 'update']);

Route::delete('/products/{id}', [ProductController::class, 'delete']);

Route::post('/filter', [ProductController::class, 'filterByCategory']);

Route::middleware('auth:api')->group(function () {
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::delete('/cart/{cartItemId}', [CartController::class, 'removeFromCart']);

    Route::post('/makePayment', [PaymentController::class, 'makePayment']);

    Route::post('/handlepayment', [PaymentController::class, 'handlePayment']);
    
});

Route::post('/placeOrder', [PaymentController::class, 'placeOrder']);

Route::get('form-data', [PaymentController::class, 'getAllFormData']);
