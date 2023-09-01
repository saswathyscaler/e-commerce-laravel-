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

        foreach ($cartItems as $cartItem) {
            Odered::create([
                'user_id' => $user->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'order_date' => now(),
            ]);

            $cartItem->delete();
        }

        return response()->json(['message' => 'Cart items have been ordered successfully']);
    }
    public function singleUserOrder(Request $request)
    {
        $user = $request->user();

        $orderedItems = Odered::where('user_id', $user->id)->get();

        return response()->json(['ordered_items' => $orderedItems]);
    }



    public function index()
    {
        $products = Odered::all();

        return response()->json(['products' => $products], 200);
    }
}