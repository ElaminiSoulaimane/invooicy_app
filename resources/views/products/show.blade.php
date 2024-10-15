<x-app-layout>
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-semibold mb-8 text-gray-800">{{ __('messages.product_details') }}</h1>

        <div class="flex flex-col lg:flex-row bg-white rounded-lg shadow-md p-6 space-y-6 lg:space-y-0 lg:space-x-8">
            <!-- Product Details Section -->
            <div class="flex-1">
                <div class="mb-4">
                    <span class="text-lg font-medium text-gray-700">{{ __('messages.name') }}:</span>
                    <span class="text-gray-900">{{ $product->name }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-lg font-medium text-gray-700">{{ __('messages.price') }}:</span>
                    <span class="text-gray-900">{{ $product->price }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-lg font-medium text-gray-700">{{ __('messages.buy_price') }}:</span>
                    <span class="text-gray-900">{{ $product->buy_price }}</span>
                </div>
                <div class="mb-4">
                    <span class="text-lg font-medium text-gray-700">{{ __('messages.quantity') }}:</span>
                    <span class="text-gray-900">{{ $product->quantity }}</span>
                </div>
            </div>

            <!-- Product Image Section -->
            <div class="flex-shrink-0">
                <div class="bg-gray-100 p-4 rounded-lg">
                    <strong class="block mb-2 text-gray-700">{{ __('messages.img') }}:</strong>
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                            class="w-40 h-40 object-cover rounded-lg border border-gray-200">
                    @else
                        <img src="{{ asset('images/Default_product.png') }}" alt="No Image"
                                    class="w-40 h-40 object-cover rounded-lg border border-gray-200">
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex space-x-4">
            <a href="{{ route('products.edit', $product->id) }}"
                class="bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition">
                {{ __('messages.edit_product') }}
            </a>
            <a href="{{ route('products.index') }}"
                class="bg-gray-600 text-white px-6 py-3 rounded-full hover:bg-gray-700 transition">
                {{ __('messages.back_to_product_list') }}
            </a>
        </div>
    </div>
</x-app-layout>
