<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Record Payment for Invoice #{{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Invoice Details</h3>
                    <p><strong>Client:</strong> {{ $invoice->client->name }}</p>

                    <p><strong>Subtotal:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
                    <p><strong>Discount:</strong> ${{ number_format($invoice->discount_amount, 2) }}</p>
                    <p><strong>Total Due:</strong> ${{ number_format($invoice->discounted_total, 2) }}</p>
                    <p><strong>Remaining Balance:</strong> ${{ number_format($remainingAmount, 2) }}</p>
                </div>
            </div>

            <div class="mt-5 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('invoices.payments.store', $invoice) }}">
                        @csrf
                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                        <!-- Payment amount input -->
                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Payment
                                Amount</label>
                            {{-- <div class="flex  rounded-[26px] shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">$</span>
                                <input type="number" name="amount" id="amount" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" step="0.01" min="0.01" max="{{ $remainingAmount }}" required>
                            </div> --}}
                            <div class="mb-4">

                                <input type="number" name="amount" id="amount"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full  rounded-[26px] sm:text-sm border-gray-300"
                                    step="0.01" min="0.01" max="{{ $remainingAmount }}"
                                    placeholder="Enter amount" required>
                                @error('amount')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            @error('amount')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment date input -->
                        <div class="mb-4">
                            <label for="payment_date" class="block text-gray-700 text-sm font-bold mb-2">Payment
                                Date</label>
                            <input type="datetime-local" name="payment_date" id="payment_date"
                                class="shadow appearance-none border rounded-[26px] w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            @error('payment_date')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit button -->
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-[26px] focus:outline-none focus:shadow-outline">
                                Record Payment
                            </button>
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
