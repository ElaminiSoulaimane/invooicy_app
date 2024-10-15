<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.client_details') }}</h1>

        <div class="mb-4 flex items-center">
            <i class="fas fa-user mr-2 text-perf-green"></i>
            <strong>{{ __('messages.name') }}:</strong> {{ $client->name }}
        </div>
        <div class="mb-4 flex items-center">
            <i class="fas fa-envelope mr-2 text-perf-green"></i>
            <strong>{{ __('messages.email') }}:</strong> {{ $client->email }}
        </div>
        <div class="mb-4 flex items-center">
            <i class="fas fa-phone mr-2 text-perf-green"></i>
            <strong>{{ __('messages.phone') }}:</strong> {{ $client->phone }}
        </div>
        <div class="mb-4 flex items-center">
            <i class="fas fa-map-marker-alt mr-2 text-perf-green"></i>
            <strong>{{ __('messages.address') }}:</strong> {{ $client->address }}
        </div>

        <div class="flex">
            <a href="{{ route('clients.edit', $client->id) }}"
                class="bg-perf-green   text-perf-black  px-4 py-2 rounded-[25px] flex items-center">
                <i class="fas fa-edit mr-2"></i>{{ __('messages.edit_client') }}
            </a>
            <a href="{{ route('clients.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-[25px] ml-2 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>{{ __('messages.back_to_client_list') }}
            </a>
        </div>
    </div>
</x-app-layout>
