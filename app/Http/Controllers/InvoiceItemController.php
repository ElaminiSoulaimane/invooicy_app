<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\Invoice; // Import Invoice model
use App\Models\Product; // Import Product model
use App\Models\Service; // Import Service model
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    // Display a listing of the invoice items
    public function index()
    {
        $invoiceItems = InvoiceItem::with(['invoice', 'product', 'service'])->get(); // Retrieve all invoice items
        return view('invoice_items.index', compact('invoiceItems')); // Return the view with invoice items
    }

    // Show the form for creating a new invoice item
    public function create()
    {
        $invoices = Invoice::all(); // Retrieve all invoices for the dropdown
        $products = Product::all(); // Retrieve all products for the dropdown
        $services = Service::all(); // Retrieve all services for the dropdown
        return view('invoice_items.create', compact('invoices', 'products', 'services')); // Return the view for creating an invoice item
    }

    // Store a newly created invoice item in storage
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'product_id' => 'nullable|exists:products,id',
            'service_id' => 'nullable|exists:services,id',
            'quantity' => 'required_if:product_id,exists:products,id|integer',
            'price' => 'required|numeric',
        ]);

        $invoiceItem = InvoiceItem::create($request->all()); // Create the invoice item

        return redirect()->route('invoice_items.index')->with('success', 'Invoice item created successfully.'); // Redirect with success message
    }

    // Display the specified invoice item
    public function show(InvoiceItem $invoiceItem)
    {
        return view('invoice_items.show', compact('invoiceItem')); // Return the view with invoice item details
    }

    // Show the form for editing the specified invoice item
    public function edit(InvoiceItem $invoiceItem)
    {
        $invoices = Invoice::all(); // Retrieve all invoices for the dropdown
        $products = Product::all(); // Retrieve all products for the dropdown
        $services = Service::all(); // Retrieve all services for the dropdown
        return view('invoice_items.edit', compact('invoiceItem', 'invoices', 'products', 'services')); // Return the view for editing an invoice item
    }

    // Update the specified invoice item in storage
    public function update(Request $request, InvoiceItem $invoiceItem)
    {
        $request->validate([
            'invoice_id' => 'sometimes|required|exists:invoices,id',
            'product_id' => 'nullable|exists:products,id',
            'service_id' => 'nullable|exists:services,id',
            'quantity' => 'required_if:product_id,exists:products,id|integer',
            'price' => 'sometimes|required|numeric',
        ]);

        $invoiceItem->update($request->all()); // Update the invoice item

        return redirect()->route('invoice_items.index')->with('success', 'Invoice item updated successfully.'); // Redirect with success message
    }

    // Remove the specified invoice item from storage
    public function destroy(InvoiceItem $invoiceItem)
    {
        $invoiceItem->delete(); // Delete the invoice item

        return redirect()->route('invoice_items.index')->with('success', 'Invoice item deleted successfully.'); // Redirect with success message
    }
}
