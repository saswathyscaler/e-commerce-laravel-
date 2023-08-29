<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//User 
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/user/{id}', [UserController::class, 'getUser']);
});

Route::get('/users', [UserController::class, 'getAllUsers']);


//ADMIN ROUTES
// Routes accessible only to admin users

Route::group(['middleware' => 'admin.auth'], function () {
    Route::post('/addproduct', [ProductController::class, 'store']);

    Route::post('/products/{id}', [ProductController::class, 'update']);

    Route::delete('/products/{id}', [ProductController::class, 'delete']);

    Route::delete('/ratings/{id}', [RatingController::class, 'deleteRating'])->middleware('auth:api');

    Route::put('/users/{id}/toggle', [UserController::class, 'toggleActivation']);
});


//products


Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{id}', [ProductController::class, 'show']);

Route::post('/filter', [ProductController::class, 'filterByCategory']);




// activity for orders
Route::middleware('auth:api')->group(function () {
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::delete('/cart/{cartItemId}', [CartController::class, 'removeFromCart']);

    //payment 

    Route::post('/makePayment', [PaymentController::class, 'makePayment']);
    Route::post('/handlepayment', [PaymentController::class, 'handlePayment']);


    //wishlist 
    Route::post('/wishlist/add/{productId}', [WishListController::class, 'addToWishlistItem']);

    Route::get('/wishlist', [WishlistController::class, 'showWishlist']);

    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'removeFromWishlist']);
});

Route::post('/placeOrder', [PaymentController::class, 'placeOrder']);

Route::get('form-data', [PaymentController::class, 'getAllFormData']);



//Rating of products

Route::post('/ratings', [RatingController::class, 'store'])->middleware('auth:api');

Route::get('/ratings/{product_id}', [RatingController::class, 'getAllRatingsForProduct']);

Route::put('/ratings/edit/{id}', [RatingController::class, 'editRating'])->middleware('auth:api');
