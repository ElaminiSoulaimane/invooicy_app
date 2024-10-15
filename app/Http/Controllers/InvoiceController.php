<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'payments'])
            ->latest()
            ->paginate(10);

        foreach ($invoices as $invoice) {
            $paidAmount = $invoice->payments->sum('amount');
            $invoice->remaining_balance = max(0, $invoice->total_amount - $paidAmount);
        }

        return view('invoices.index', compact('invoices'));
    }
    public function create()
    {
        $clients = Client::all();
        $products = Product::all();
        $services = Service::all();
        return view('invoices.create', compact('clients', 'products', 'services'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required_if:items.*.type,product|integer|min:1',
            'discount_amount' => 'required|numeric|min:0',
        ]);

        $invoice = DB::transaction(function () use ($request) {
            $invoice = new Invoice();
            $invoice->client_id = $request->client_id;
            $invoice->user_id = auth()->id();
            $invoice->status = 'unpaid';
            $invoice->invoice_number = 'INV-' . time();
            $invoice->discount_amount = $request->discount_amount;
            $invoice->save();

            $totalAmount = 0;

            // ... (rest of the method remains the same)

            $discountedTotal = max($totalAmount - $invoice->discount_amount, 0);

            $invoice->update([
                'total_amount' => $totalAmount,
                'discounted_total' => $discountedTotal,
                'remaining_balance' => $discountedTotal
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    // public function show(Invoice $invoice)
    // {
    //     $invoice->load(['client', 'items.product', 'items.service', 'payments']);
    //     return view('invoices.show', compact('invoice'));
    // }
    // public function show(Invoice $invoice)
    // {
    //     $invoice->load(['client', 'items.product', 'items.service', 'payments.user']);
    //     return view('invoices.show', compact('invoice'));
    // }
    public function show($id)
    {
        $invoice = Invoice::with('client', 'items.product', 'items.service')->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        try {
            DB::transaction(function () use ($invoice) {
                // Restore product quantities
                foreach ($invoice->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }

                // Delete related payments
                $invoice->payments()->delete();

                // Delete invoice items
                $invoice->items()->delete();

                // Delete the invoice
                $invoice->delete();
            });

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('invoices.index')
                ->with('error', 'Failed to delete invoice. ' . $e->getMessage());
        }
    }
}
