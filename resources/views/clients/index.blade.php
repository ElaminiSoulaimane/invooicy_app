<x-app-layout>
    <div class="container mx-auto py-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl  font-bold relative inline-block " id="headingElement"
                style="max-width:25ch; padding-bottom: 0.5rem;">
                {{ __('messages.client_list') }}
                <span id="borderSpan" class="absolute left-0 bottom-0 border-b-4 border-perf-green"></span>
            </h1>

            <a href="{{ route('clients.create') }}"
                class="bg-perf-black text-perf-green px-4 py-2 rounded-[26px] ml-4">{{ __('messages.add_new_client') }}</a>
        </div>

        <!-- Add search input -->
        <div class="mb-4">
            <input type="text" id="search-input" placeholder="{{ __('messages.search_clients') }}"
                class="w-full px-4 py-2 rounded-[26px] border border-gray-300 focus:outline-none focus:ring-2 focus:ring-perf-green">
        </div>

        <!-- Add search results container -->
        <div id="search-results" class="mb-4 hidden">
            <!-- Search results will be dynamically inserted here -->
        </div>

        <table class="min-w-full bg-white mt-6" id="clients-table">
            <thead class="bg-perf-green">
                <tr>
                    <th class="px-4 py-2 rounded-tl-[26px]">{{ __('messages.name') }}</th>
                    <th class="border px-4 py-2">{{ __('messages.email') }}</th>
                    <th class="border px-4 py-2">{{ __('messages.phone') }}</th>
                    <th class="border px-4 py-2">{{ __('messages.address') }}</th>
                    <th class="px-4 py-2 rounded-tr-[26px]">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td class="border px-4 py-2">{{ $client->name }}</td>
                        <td class="border px-4 py-2">{{ $client->email }}</td>
                        <td class="border px-4 py-2">{{ $client->phone }}</td>
                        <td class="border px-4 py-2">{{ $client->address }}</td>
                        <td class="border px-4 py-2 flex items-center space-x-2">
                            <a href="{{ route('clients.show', $client->id) }}"
                                class="bg-perf-green text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green"
                                aria-label="{{ __('messages.view') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('clients.edit', $client->id) }}"
                                class="bg-green-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green"
                                aria-label="{{ __('messages.edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green"
                                    aria-label="{{ __('messages.delete') }}">
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
    // ... (keep existing code for updateBorderWidth)

    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const clientsTable = document.getElementById('clients-table');
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
                clientsTable.classList.remove('hidden');
            }
        }, 300);
    });

    async function fetchResults(query) {
        try {
            const response = await fetch(`/api/clients/search?query=${encodeURIComponent(query)}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            displayResults(data);
        } catch (error) {
            console.error('Error fetching results:', error);
            searchResults.innerHTML = `<p class="text-center py-4 text-red-500">Error: ${error.message}</p>`;
            searchResults.classList.remove('hidden');
            clientsTable.classList.add('hidden');
        }
    }

    function displayResults(clients) {
        searchResults.innerHTML = '';
        if (clients.length > 0) {
            const table = document.createElement('table');
            table.className = 'min-w-full bg-white';
            table.innerHTML = `
                <thead class="bg-perf-green">
                    <tr>
                        <th class="px-4 py-2 rounded-tl-[26px]">${'{{ __('messages.name') }}'}</th>
                        <th class="border px-4 py-2">${'{{ __('messages.email') }}'}</th>
                        <th class="border px-4 py-2">${'{{ __('messages.phone') }}'}</th>
                        <th class="px-4 py-2 rounded-tr-[26px]">${'{{ __('messages.actions') }}'}</th>
                    </tr>
                </thead>
                <tbody>
                    ${clients.map(client => `
                        <tr>
                            <td class="border px-4 py-2">${client.name}</td>
                            <td class="border px-4 py-2">${client.email}</td>
                            <td class="border px-4 py-2">${client.phone}</td>
                            <td class="border px-4 py-2 flex items-center space-x-2">
                                <a href="/clients/${client.id}" class="bg-perf-green text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="${'{{ __('messages.view') }}'}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/clients/${client.id}/edit" class="bg-green-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="${'{{ __('messages.edit') }}'}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/clients/${client.id}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white p-2 rounded-[26px] transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="${'{{ __('messages.delete') }}'}">
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
            clientsTable.classList.add('hidden');
        } else {
            searchResults.innerHTML = '<p class="text-center py-4">No results found</p>';
            searchResults.classList.remove('hidden');
            clientsTable.classList.add('hidden');
        }
    }
</script>
</x-app-layout>