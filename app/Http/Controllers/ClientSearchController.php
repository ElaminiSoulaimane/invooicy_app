<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        $clients = Client::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);

        return response()->json($clients);
    }
}
