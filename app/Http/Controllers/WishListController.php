<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishListController extends Controller
{

    //ADD TO WISHLIST
    public function addToWishlistItem(Request $request, $productId)
    {
        $user = auth()->user();
        $product = Product::findOrFail($productId);
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlistItem) {
            return response()->json(['message' => 'Product already wishlisted']);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return response()->json(['message' => 'Product wishlisted']);
    }



    //VIEW WISHLISTED ITEMS

    public function showWishlist(Request $request)
    {
        $user = $request->user();

        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->with('product')
            ->get();

        return response()->json($wishlistItem);
    }

    //get singlewishlist

    public function getWishlistItem($id)
    {
        $user = auth()->user();
    
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('id', $id)
            ->with('product')
            ->first();
    
        if (!$wishlistItem) {
            return response()->json(['message' => 'Wishlist item not found'], 404);
        }
    
        return response()->json($wishlistItem);
    }
    
    //REMOVE FROM Wishlist
    public function removeFromWishlist($productId)
    {
        $user = auth()->user();
        $product = Product::findOrFail($productId);
        
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
    
        if (!$wishlistItem) {
            return response()->json(['message' => 'Product not found in wishlist'], 404);
        }
    
        $wishlistItem->delete();
    
        return response()->json(['message' => 'Product removed from wishlist']);
    }
    
}
