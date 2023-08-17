<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|string', 
        ]);

        $product = Product::create($validatedData);

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    public function index()
    {
        $products = Product::all();

        return response()->json(['products' => $products], 200);
    }



    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product], 200);
    }



}
