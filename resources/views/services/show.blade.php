<x-app-layout>
                <x-slot name="title">Services - Invooicy</x-slot>

    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.service_details') }}</h1>
        <div class="mb-4">
            <strong>{{ __('messages.name') }}:</strong> {{ $service->name }}
        </div>
        <div class="mb-4">
            <strong>{{ __('messages.price') }}:</strong> {{ $service->price }}
        </div>
        <a href="{{ route('services.edit', $service->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-[26px]">
            {{ __('messages.edit_service') }}
        </a>
        <a href="{{ route('services.index') }}" class="bg-perf-green rounded-[26px]   text-perf-black  px-4 py-2 ml-2">
            {{ __('messages.back_to_service_list') }}
        </a>
    </div>
</x-app-layout>
