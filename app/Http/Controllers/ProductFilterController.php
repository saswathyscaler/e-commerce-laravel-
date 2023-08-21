<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductFilterController extends Controller
{
    public function filterByCategory(Request $request)
    {
        $category = $request->input('category');

        $filteredProducts = Product::where('category', $category)->get();

        if ($filteredProducts->isEmpty()) {
            return response()->json(['message' => 'No products found for the specified category'], 404);
        }

        return response()->json(['products' => $filteredProducts], 200);
    }
}
