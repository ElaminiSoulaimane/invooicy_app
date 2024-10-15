<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Display a listing of the services
    public function index()
    {
        $services = Service::all(); // Retrieve all services
        return view('services.index', compact('services')); // Return the view with services
    }

    // Show the form for creating a new service
    public function create()
    {
        return view('services.create'); // Return the view for creating a service
    }

    // Store a newly created service in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $service = Service::create($request->all()); // Create the service

        return redirect()->route('services.index')->with('success', 'Service created successfully.'); // Redirect with success message
    }

    // Display the specified service
    public function show(Service $service)
    {
        return view('services.show', compact('service')); // Return the view with service details
    }

    // Show the form for editing the specified service
    public function edit(Service $service)
    {
        return view('services.edit', compact('service')); // Return the view for editing a service
    }

    // Update the specified service in storage
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
        ]);

        $service->update($request->all()); // Update the service

        return redirect()->route('services.index')->with('success', 'Service updated successfully.'); // Redirect with success message
    }

    // Remove the specified service from storage
    public function destroy(Service $service)
    {
        $service->delete(); // Delete the service

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.'); // Redirect with success message
    }
}
