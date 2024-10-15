<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.add_new_client') }}</h1>
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.name') }}</label>
                <input type="text" name="name" id="name"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('messages.email') }}</label>
                <input type="email" name="email" id="email"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('messages.phone') }}</label>
                <input type="text" name="phone" id="phone"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm">
            </div>
            <div class="mb-4">
                <label for="address"
                    class="block text-sm font-medium text-gray-700">{{ __('messages.address') }}</label>
                <input type="text" name="address" id="address"
                    class="mt-1 block w-full border-perf-green rounded-[26px] shadow-sm">
            </div>
            <div class="mb-4">
                <button type="submit"
                    class=" bg-perf-black  border border-perf-green text-perf-green px-4 py-2 rounded-[26px] mt-1 block w-full
                    hover:bg-perf-green hover:  text-perf-black  hover:border-black
                    transition-colors duration-300 ease-in-out">
                    {{ __('messages.create_client') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
