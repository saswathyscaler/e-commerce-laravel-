<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Odered;

class OrderedController extends Controller

   
{
    public function store(Request $request)
    {
        $user = $request->user();

        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items in the cart to order'], 400);
        }

        foreach ($cartItems as $cartItem) {
            Odered::create([
                'user_id' => $user->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
            ]);

            $cartItem->delete();
        }

        return response()->json(['message' => 'Cart items have been ordered successfully']);
    }
}