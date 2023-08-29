<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //ADD PRODUCT
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $product = Product::create($validatedData);


        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    //ALL PRODUCT 
    public function index()
    {
        $products = Product::all();

        return response()->json(['products' => $products], 200);
    }


    //SINGLE PRODUCT
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product], 200);
    }

    //UPDATE PRODUCT
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string',
            'category' => 'string',
            'description' => 'nullable|string',
            'price' => 'integer',
            'stock_quantity' => 'integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $product->update($validatedData);

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    //DELET PRODUCT 
    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    //FILTER PRODUCT BY CATEGORIES 
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
