<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart; 

class CartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
    
        $user = auth()->user();

        $product = Product::findOrFail($productId);

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return response()->json(['message' => 'Product added to cart successfully']);
    }


    public function index(Request $request)
    {
        $user = $request->user();
        
        $cartItems = Cart::where('user_id', $user->id)
            ->with('product') // Load the associated product data
            ->get();
        
        return response()->json($cartItems);
    }
}
