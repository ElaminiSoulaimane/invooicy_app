<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Display a listing of the clients
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();

        return view('clients.index', compact('clients')); // Return the view with clients
    }

    // Show the form for creating a new client
    public function create()
    {
        return view('clients.create'); // Return the view for creating a client
    }

    // Store a newly created client in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        Client::create($request->all()); // Create the client

        return redirect()->route('clients.index')->with('success', 'Client created successfully.'); // Redirect with success message
    }

    // Display the specified client
    public function show(Client $client)
    {
        return view('clients.show', compact('client')); // Return the view with client details
    }

    // Show the form for editing the specified client
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client')); // Return the view for editing a client
    }

    // Update the specified client in storage
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $client->update($request->all()); // Update the client

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.'); // Redirect with success message
    }

    // Remove the specified client from storage
    public function destroy(Client $client)
    {
        $client->delete(); // Delete the client

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.'); // Redirect with success message
    }
}
