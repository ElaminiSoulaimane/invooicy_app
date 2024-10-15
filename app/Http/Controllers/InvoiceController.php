<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'payments']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('invoice_number', 'like', "%$search%");
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'paid') {
                $query->whereRaw('total_amount <= (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE invoice_id = invoices.id)');
            } elseif ($status === 'partially_paid') {
                $query->whereRaw('total_amount > (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE invoice_id = invoices.id)')
                ->whereHas('payments');
            } elseif ($status === 'unpaid') {
                $query->doesntHave('payments');
            }
        }

        $invoices = $query->latest()->paginate(10);

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
            $invoice = Invoice::create([
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'status' => 'unpaid',
                'invoice_number' => 'INV-' . time(),
                'discount_amount' => $request->discount_amount,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $unitPrice = 0;
                $totalPrice = 0;
                $quantity = $item['type'] === 'product' ? $item['quantity'] : 1;

                if ($item['type'] === 'product') {
                    $product = Product::findOrFail($item['id']);
                    $unitPrice = $product->price;
                    $totalPrice = $unitPrice * $quantity;

                    $invoice->items()->create([
                        'product_id' => $product->id,
                        'type' => 'product',
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'price' => $unitPrice,
                    ]);

                    $product->decrement('quantity', $quantity);
                } else {
                    $service = Service::findOrFail($item['id']);
                    $unitPrice = $service->price;
                    $totalPrice = $unitPrice;

                    $invoice->items()->create([
                        'service_id' => $service->id,
                        'type' => 'service',
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'price' => $unitPrice,
                    ]);
                }

                $totalAmount += $totalPrice;
            }

            $discountedTotal = max($totalAmount - $invoice->discount_amount, 0);

            $invoice->update([
                'total_amount' => $totalAmount,
                'discounted_total' => $discountedTotal,
                'remaining_balance' => $discountedTotal,
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with('client', 'items.product', 'items.service', 'payments')->findOrFail($id);
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
    public function edit(Invoice $invoice)
    {
        $clients = Client::all();
        $products = Product::all();
        $services = Service::all();
        return view('invoices.edit', compact('invoice', 'clients', 'products', 'services'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // Validation logic here (similar to store method)
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|exists:products,id|exists:services,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            // Update invoice details
            $invoice->update([
                'client_id' => $request->client_id,
                'discount_amount' => $request->discount_amount,
            ]);

            // Delete old items
            $invoice->items()->delete();

            // Add new items (similar to store method)
            $totalAmount = 0;
            foreach ($request->items as $item) {
                if ($item['type'] === 'product') {
                    $product = Product::findOrFail($item['id']);
                    $totalAmount += $product->price * $item['quantity'];

                    // Logic to create new product item
                    $invoice->items()->create([
                        'type' => 'product',
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                } elseif ($item['type'] === 'service') {
                    $service = Service::findOrFail($item['id']);
                    $totalAmount += $service->price * $item['quantity'];

                    // Logic to create new service item
                    $invoice->items()->create([
                        'type' => 'service',
                        'service_id' => $service->id,
                        'quantity' => $item['quantity'],
                        'price' => $service->price,
                    ]);
                }
            }

            // Update invoice totals
            $discountedTotal = max($totalAmount - $invoice->discount_amount, 0);
            $invoice->update([
                'total_amount' => $totalAmount,
                'discounted_total' => $discountedTotal,
                'remaining_balance' => $discountedTotal - $invoice->payments->sum('amount'),
            ]);
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }


}
