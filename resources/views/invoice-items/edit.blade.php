<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Edit Invoice Item</h1>
        <form action="{{ route('invoice-items.update', $invoiceItem->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="product_service_id" class="block text-sm font-medium text-gray-700">Product/Service</label>
                <select name="product_service_id" id="product_service_id"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ $product->id == $invoiceItem->product_service_id ? 'selected' : '' }}>
                            {{ $product->name }}</option>
                    @endforeach
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}"
                            {{ $service->id == $invoiceItem->product_service_id ? 'selected' : '' }}>
                            {{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" id="quantity"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm"
                    value="{{ $invoiceItem->quantity }}" required>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" id="price"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm"
                    value="{{ $invoiceItem->price }}" step="0.01" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-[26px]">Update Invoice Item</button>
        </form>
    </div>
</x-app-layout>
