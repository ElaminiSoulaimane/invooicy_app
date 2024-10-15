<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.edit_product') }}</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.name') }}</label>
                <input type="text" name="name" id="name"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm" value="{{ $product->name }}"
                    required>
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">{{ __('messages.price') }}</label>
                <input type="number" name="price" id="price"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm" value="{{ $product->price }}"
                    step="0.01" required>
            </div>

            <div class="mb-4">
                <label for="buy_price"
                    class="block text-sm font-medium text-gray-700">{{ __('messages.buy_price') }}</label>
                <input type="number" name="buy_price" id="buy_price"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm"
                    value="{{ $product->buy_price }}" step="0.01">
            </div>

            <div class="mb-4">
                <label for="quantity"
                    class="block text-sm font-medium text-gray-700">{{ __('messages.quantity') }}</label>
                <input type="number" name="quantity" id="quantity"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm"
                    value="{{ $product->quantity }}" required>
            </div>

            <button type="submit"
                class=" bg-perf-black  border border-perf-green text-perf-green px-4 py-2 rounded-[26px] mt-1 block w-full
                    hover:bg-perf-green hover:  text-perf-black  hover:border-black
                    transition-colors duration-300 ease-in-out">{{ __('messages.update_product') }}</button>
        </form>
    </div>
</x-app-layout>
