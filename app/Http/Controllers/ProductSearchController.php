<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('price', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'price', 'quantity']);

        return response()->json($products);
    }
}
