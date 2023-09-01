<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{

    //ADD PRODUCT TO THE CART
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

    //VIEW CART ITEMS
    public function index(Request $request)
    {
        $user = $request->user();

        $cartItems = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        return response()->json($cartItems);
    }

    //REMOVE FROM CART
    public function removeFromCart($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);

        if (!$cartItem) {
            return response()->json(['message' => 'product not found'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart successfully']);
    }



    public function clearCart(Request $request)
    {
        $user = $request->user();

        Cart::where('user_id', $user->id)->delete();

        return response()->json(['message' => 'Cart has been cleared successfully']);
    }





}
