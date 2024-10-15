<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        // Search services by name or price
        $services = Service::where('name', 'like', "%{$query}%")
            ->orWhere('price', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'price']);

        // Return the search results as JSON
        return response()->json($services);
    }
}
