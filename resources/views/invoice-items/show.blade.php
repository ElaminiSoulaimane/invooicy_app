<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Invoice Item Details</h1>
        <div class="mb-4">
            <strong>Product/Service:</strong> {{ $invoiceItem->product_service->name }}
        </div>
        <div class="mb-4">
            <strong>Quantity:</strong> {{ $invoiceItem->quantity }}
        </div>
        <div class="mb-4">
            <strong>Price:</strong> {{ $invoiceItem->price }}
        </div>
        <div class="mb-4">
            <strong>Total:</strong> {{ $invoiceItem->quantity * $invoiceItem->price }}
        </div>
        <a href="{{ route('invoice-items.edit', $invoiceItem->id) }}"
            class="bg-green-500 text-white px-4 py-2 rounded-[26px]">Edit Invoice Item</a>
        <a href="{{ route('invoice-items.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-[25px] ml-2">Back to
            Invoice Item List</a>
    </div>
</x-app-layout>
