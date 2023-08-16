<?php

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

Route::post('/addproduct', [ProductController::class, 'addProduct']);

Route::post('/addproduct', [ProductController::class, 'store']);

Route::get('/products', [ProductController::class, 'index']);

