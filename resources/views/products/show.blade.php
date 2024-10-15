<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.product_details') }}</h1>
        <div class="mb-4">
            <strong>{{ __('messages.name') }}:</strong> {{ $product->name }}
        </div>
        <div class="mb-4">
            <strong>{{ __('messages.price') }}:</strong> {{ $product->price }}
        </div>
        <div class="mb-4">
            <strong>{{ __('messages.buy_price') }}:</strong> {{ $product->buy_price }}
        </div>
        <div class="mb-4">
            <strong>{{ __('messages.quantity') }}:</strong> {{ $product->quantity }}
        </div>
        <a href="{{ route('products.edit', $product->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-[26px]">{{ __('messages.edit_product') }}</a>
        <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-[25px] ml-2">{{ __('messages.back_to_product_list') }}</a>
    </div>
</x-app-layout>
