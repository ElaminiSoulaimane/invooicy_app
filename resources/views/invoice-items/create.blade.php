<!-- 1. resources/views/invoices/create.blade.php -->
<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Create New Invoice</h1>

        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" value="{{ $nextInvoiceNumber }}"
                    readonly>
            </div>

            <div class="mb-4">
                <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                <select name="client_id" id="client_id"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" required>
                    <option value="">Select a Client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
                <input type="number" name="total_amount" id="total_amount"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" step="0.01" required>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" required>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-[26px]">Create Invoice</button>
        </form>
    </div>
</x-app-layout>
