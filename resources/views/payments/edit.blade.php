<x-app-layout>
    <x-slot name="title">Invoice - Invooicy</x-slot>

    <div class="container mx-auto py-8">
        
        <h1 class="text-2xl font-bold mb-4">Edit Payment</h1>
        <form action="{{ route('payments.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="invoice_id" class="block text-sm font-medium text-gray-700">Invoice ID</label>
                <input type="text" name="invoice_id" id="invoice_id"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm"
                    value="{{ $payment->invoice_id }}" required>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                <input type="number" name="amount" id="amount"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" value="{{ $payment->amount }}"
                    step="0.01" required>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" id="date"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm"
                    value="{{ $payment->date->format('Y-m-d') }}" required>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" required>
                    <option value="Paid" {{ $payment->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Partially Paid" {{ $payment->status == 'Partially Paid' ? 'selected' : '' }}>
                        Partially Paid</option>
                    <option value="Unpaid" {{ $payment->status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-[26px]">Update Payment</button>
        </form>
    </div>
</x-app-layout>
