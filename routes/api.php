<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\demoOrder;
use App\Http\Controllers\OrderedController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//User 
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/login-google', [UserController::class, 'loginG']);


Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/filter', [ProductController::class, 'filterByCategory']);


Route::middleware('auth:api')->group(function () {
    Route::get('/user/{id}', [UserController::class, 'getUser']);
});

Route::get('/users', [UserController::class, 'getAllUsers']);


//ADMIN ROUTES
// Routes accessible only to admin users

Route::middleware(['admin', 'auth:api'])->group(function () {
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/ratings/{id}', [RatingController::class, 'deleteRating'])->middleware('auth:api');

    Route::post('/addproduct', [ProductController::class, 'store']);

    Route::delete('/coupons/{id}/delete', [CouponController::class, 'deleteCoupon']);
});

Route::put('/users/{id}/toggle', [UserController::class, 'toggleActivation']);
Route::post('/coupons/create', [CouponController::class, 'createCoupon']);

Route::post('/coupons/{id}/edit', [CouponController::class, 'editCoupon']);

//products

Route::delete('/products/{id}', [ProductController::class, 'delete']);
Route::get('/coupons/{id}', [CouponController::class, 'showCoupon']);

Route::get('/coupons', [CouponController::class, 'getAllCoupons']);



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

    Route::get('/wishlist/{id}', [WishlistController::class, 'getWishlistItem']);


    Route::delete('/cart', [CartController::class, 'clearCart']);


    Route::get('/ordered-items', [OrderedController::class, 'singleUserOrder']);
    // orders

    Route::post('/order', [OrderedController::class, 'store']);
});
Route::get('allorders', [OrderedController::class, 'index']);
Route::put('/order-status/{order_id}', [OrderedController::class, 'updateOrderStatus']);

Route::post('/placeOrder', [PaymentController::class, 'placeOrder']);

Route::get('form-data', [PaymentController::class, 'getAllFormData']);



//Rating of products

Route::post('/ratings', [RatingController::class, 'store'])->middleware('auth:api');

Route::get('/ratings/{product_id}', [RatingController::class, 'getAllRatingsForProduct']);

Route::put('/ratings/edit/{id}', [RatingController::class, 'editRating'])->middleware('auth:api');



Route::middleware('auth:api')->get('/test', function () {
    return Auth::user()->email;
});




Route::get('/sendemail', [demoOrder::class, 'index']);


Route::get('/ordered-items/{product_id}', 'OrderedController@getUserSpecificOrderedProduct');
