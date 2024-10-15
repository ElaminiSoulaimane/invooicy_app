<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Invoice Details') }}
        </h2>
    </x-slot>

    <!-- Invoice details section -->

    <!-- Add the Payments nav link here -->
    <x-nav-link :href="route('invoices.payments.create', $invoice)" :active="request()->routeIs('payments.*')">
        {{ __('Payments') }}
    </x-nav-link>


    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Payment Details</h1>
        <div class="mb-4">
            <strong>Invoice ID:</strong> {{ $payment->invoice_id }}
        </div>
        <div class="mb-4">
            <strong>Amount:</strong> {{ $payment->amount }}
        </div>
        <div class="mb-4">
            <strong>Date:</strong> {{ $payment->date->format('Y-m-d') }}
        </div>
        <div class="mb-4">
            <strong>Status:</strong> {{ $payment->status }}
        </div>
        <a href="{{ route('payments.edit', $payment->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-[26px]">Edit
            Payment</a>
        <a href="{{ route('payments.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-[25px] ml-2">Back to
            Payment List</a>
    </div>
</x-app-layout>
