<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display a listing of the products
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        // $products = Product::all(); // Retrieve all products
        return view('products.index', compact('products')); // Return the view with products
    }

    // Show the form for creating a new product
    public function create()
    {
        return view('products.create'); // Return the view for creating a product
    }

    // Store a newly created product in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $product = Product::create($request->all()); // Create the product

        return redirect()->route('products.index')->with('success', 'Product created successfully.'); // Redirect with success message
    }

    // Display the specified product
    public function show(Product $product)
    {
        return view('products.show', compact('product')); // Return the view with product details
    }

    // Show the form for editing the specified product
    public function edit(Product $product)
    {
        return view('products.edit', compact('product')); // Return the view for editing a product
    }

    // Update the specified product in storage
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'quantity' => 'sometimes|required|integer',
            'buy_price' => 'nullable|numeric',
        ]);

        $product->update($request->all()); // Update the product

        return redirect()->route('products.index')->with('success', 'Product updated successfully.'); // Redirect with success message
    }

    // Remove the specified product from storage
    public function destroy(Product $product)
    {
        $product->delete(); // Delete the product

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.'); // Redirect with success message
    }
}