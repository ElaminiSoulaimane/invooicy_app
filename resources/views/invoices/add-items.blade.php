<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Add Items to Invoice #{{ $invoice->invoice_number }}</h1>

        <form action="{{ route('invoices.store-items', $invoice->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                <select name="product_id" id="product_id"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm">
                    <option value="">Select a Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} -
                            ${{ number_format($product->price, 2) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="service_id" class="block text-sm font-medium text-gray-700">Service</label>
                <select name="service_id" id="service_id"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm">
                    <option value="">Select a Service</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} -
                            ${{ number_format($service->price, 2) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" id="quantity"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" required min="1">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" id="price"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" required step="0.01">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-[26px]">Add Item</button>
        </form>

        <!-- Display current items -->
        @if ($invoice->items->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Current Items</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $item->product->name ?? $item->service->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
