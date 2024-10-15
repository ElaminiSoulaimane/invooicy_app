<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // public function create(Invoice $invoice)
    // {
    //     $remainingAmount = $invoice->total_amount - $invoice->payments->sum('amount');
    //     return view('payments.create', compact('invoice', 'remainingAmount'));
    // }
    public function create(Invoice $invoice)
    {
        $remainingAmount = $invoice->remaining_balance;
        return view('payments.create', compact('invoice', 'remainingAmount'));
    }

    public function store(Request $request, Invoice $invoice)
    {
        $request->validate([
    'amount' => [
        'required',
        'numeric',
        'min:0.01',
    ],
    'payment_date' => 'required|date',
], [
    'amount.required' => 'Payment amount is required.',
    'amount.numeric' => 'Payment amount must be a number.',
    'payment_date.required' => 'Payment date is required.',
    'payment_date.date' => 'Payment date must be a valid date.',
]);

        DB::transaction(function () use ($request, $invoice) {
            $payment = new Payment([
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'user_id' => auth()->id(),
                // Set the invoice_id directly if needed
                'invoice_id' => $invoice->id,
            ]);

            // Save payment through the invoice relationship
            $invoice->payments()->save($payment);

            $newRemainingBalance = max(0, $invoice->remaining_balance - $request->amount);
            $invoice->update([
                'remaining_balance' => $newRemainingBalance,
                'status' => $newRemainingBalance == 0 ? 'paid' : 'partially paid',
            ]);
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }


    public function show(Payment $payment)
    {
        $payment->load(['invoice.client', 'user']);
        return view('payments.show', compact('payment'));
    }

    public function index(Invoice $invoice)
    {
        $payments = $invoice->payments()->with('user')->latest()->get();
        return view('payments.index', compact('invoice', 'payments'));
    }
    public function allPayments()
    {
        $payments = Payment::with(['invoice.client', 'user'])
            ->latest()
            ->paginate(15);
        return view('payments.index', compact('payments'));
    }
}
