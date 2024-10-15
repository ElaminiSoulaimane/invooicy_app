<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.add_new_service') }}</h1>
        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.name') }}</label>
                <input type="text" name="name" id="name"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">{{ __('messages.price') }}</label>
                <input type="number" name="price" id="price"
                    class="mt-1 block w-full border-gray-300  rounded-[26px] shadow-sm" step="0.01" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-[26px]">
                {{ __('messages.create_service') }}
            </button>
        </form>
    </div>
</x-app-layout>
