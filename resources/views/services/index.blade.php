<x-app-layout>
    <div class="container mx-auto py-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold relative inline-block max-w-[25ch] pb-2">
                {{ __('messages.service_list') }}
                <span id="borderSpan" class="absolute left-0 bottom-0 border-b-4 border-perf-green"></span>
            </h1>
            <a href="{{ route('services.create') }}" class="bg-perf-black text-perf-green px-4 py-2 rounded-[26px] ml-4">
                {{ __('messages.add_new_service') }}
            </a>
        </div>

        <!-- Search input -->
        <div class="mb-4">
            <input type="text" id="search-input" placeholder="{{ __('messages.search_services') }}"
                   class="w-full px-4 py-2 rounded-[26px] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-perf-green">
        </div>

        <!-- Search results container -->
        <div id="search-results" class="mb-4 hidden"></div>

        <!-- Services table -->
        <table class="min-w-full bg-white mt-6" id="services-table">
            <thead class="bg-perf-green ">
                <tr>
                    <th class="px-4 py-2 rounded-tl-[26px]">{{ __('messages.name') }}</th>
                    <th class="border px-4 py-2">{{ __('messages.price') }}</th>
                    <th class="px-4 py-2 rounded-tr-[26px]">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td class="border px-4 py-2">{{ $service->name }}</td>
                        <td class="border px-4 py-2">{{ $service->price }}</td>
                        <td class="border px-4 py-2 flex items-center space-x-2">
                            <a href="{{ route('services.show', $service->id) }}" class="bg-perf-green rounded-[26px] text-white p-2  transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.view_service_details') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('services.edit', $service->id) }}" class="bg-green-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.edit_service') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.delete_service') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');
        const servicesTable = document.getElementById('services-table');
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = this.value.trim();
                if (query.length > 2) {
                    fetchResults(query);
                } else {
                    searchResults.innerHTML = '';
                    searchResults.classList.add('hidden');
                    servicesTable.classList.remove('hidden');
                }
            }, 300);
        });

        async function fetchResults(query) {
            try {
                const response = await fetch(`/api/services/search?query=${encodeURIComponent(query)}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                displayResults(data);
            } catch (error) {
                console.error('Error fetching results:', error);
                searchResults.innerHTML = `<p class="text-center py-4 text-red-500">{{ __('messages.error_fetching_results') }}</p>`;
                searchResults.classList.remove('hidden');
                servicesTable.classList.add('hidden');
            }
        }

        function displayResults(services) {
            searchResults.innerHTML = '';
            if (services.length > 0) {
                const table = document.createElement('table');
                table.className = 'min-w-full bg-white';
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">{{ __('messages.name') }}</th>
                            <th class="border px-4 py-2">{{ __('messages.price') }}</th>
                            <th class="border px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${services.map(service => `
                            <tr>
                                <td class="border px-4 py-2">${service.name}</td>
                                <td class="border px-4 py-2">${service.price}</td>
                                <td class="border px-4 py-2 flex items-center space-x-2">
                                    <a href="/services/${service.id}" class="bg-perf-green text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.view_service_details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/services/${service.id}/edit" class="bg-green-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.edit_service') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/services/${service.id}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.delete_service') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                `;
                searchResults.appendChild(table);
                searchResults.classList.remove('hidden');
                servicesTable.classList.add('hidden');
            } else {
                searchResults.innerHTML = `<p class="text-center py-4">{{ __('messages.no_results_found') }}</p>`;
                searchResults.classList.remove('hidden');
                servicesTable.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
