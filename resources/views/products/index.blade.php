<x-app-layout>
    <style>
        #image-modal {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 9999;
            /* Ensure it's on top of other elements */
            opacity: 0;
            /* Start hidden */
            transition: opacity 0.3s ease;
        }

        #image-modal.hidden {
            display: none;
            /* Hide the modal */
        }

        #modal-image {
            max-width: 90%;
            /* Responsive */
            max-height: 90%;
        }
    </style>
    <div class="container mx-auto py-8">
        <!-- Notification for low stock -->
        @php
            $lowStockProducts = $products->filter(function ($product) {
                return $product->quantity < 10;
            });
        @endphp

        @if ($lowStockProducts->isNotEmpty())
            <div id="low-stock-notification"
                class="bg-perf-black border-l-4 border-perf-green text-red-500 p-4 mb-4 flex justify-between items-center shadow-lg rounded-md">
                <div>
                    <strong>{{ __('messages.low_stock_warning') }}:</strong> {{ __('messages.low_stock_message') }}
                </div>
                <button id="close-notification"
                    class="text-red-500 hover:text-red-700 focus:outline-none transition duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold relative inline-block text-gray-800" id="headingElement"
                style="max-width: 25ch; padding-bottom: 0.5rem;">
                {{ __('messages.product_list') }}
                <span id="borderSpan" class="absolute left-0 bottom-0 border-b-4 border-perf-green"></span>
            </h1>

            <a href="{{ route('products.create') }}"
                class="bg-perf-green text-white px-4 py-2 rounded-full ml-4 shadow transition duration-200 hover:bg-perf-black">
                {{ __('messages.add_new_product') }}
            </a>
        </div>

        <!-- Add search input -->
        <div class="mb-4">
            <input type="text" id="search-input" placeholder="{{ __('messages.search_products') }}"
                class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-perf-green transition duration-200">
        </div>

        <!-- Add search results container -->
        <div id="search-results" class="mb-4 hidden">
            <!-- Search results will be dynamically inserted here -->
        </div>

        <table class="min-w-full bg-white mt-6 shadow-md rounded-lg overflow-hidden" id="products-table">
            <thead class="bg-perf-green text-white">
                <tr>
                    <th class="px-4 py-2 rounded-tl-full">{{ __('messages.name') }}</th>
                    <th class="px-4 py-2">{{ __('messages.img') }}</th>
                    <th class="px-4 py-2">{{ __('messages.price') }}</th>
                    <th class="px-4 py-2">{{ __('messages.buy_price') }}</th>
                    <th class="px-4 py-2">{{ __('messages.quantity') }}</th>
                    <th class="px-4 py-2 rounded-tr-full">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="border px-4 py-2">{{ $product->name }}</td>
                        <td class="border px-4 py-2">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="w-16 h-16 object-cover rounded-full cursor-pointer"
                                    onclick="openModal('{{ Storage::url($product->image) }}')">
                            @else
                                <img src="{{ asset('images/Dufult_product.png') }}" alt="No Image"
                                    class="w-16 h-16 object-cover rounded-lg ">
                            @endif
                        </td>
                        <td class="border px-4 py-2">{{ $product->price }}</td>
                        <td class="border px-4 py-2">{{ $product->buy_price }}</td>
                        <td class="border px-4 py-2 {{ $product->quantity < 10 ? 'text-red-500' : '' }}">
                            {{ $product->quantity }}
                            @if ($product->quantity < 10)
                                <span class="inline-flex items-center bg-red-500 text-white px-1 py-1 rounded-full">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                </span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 flex items-center space-x-2">
                            <a href="{{ route('products.show', $product->id) }}"
                                class="bg-perf-green text-white p-2 rounded-full transition duration-200 hover:bg-perf-black hover:text-perf-green"
                                aria-label="{{ __('messages.view') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}"
                                class="bg-blue-600 text-white p-2 rounded-full transition duration-200 hover:bg-perf-black hover:text-perf-green"
                                aria-label="{{ __('messages.edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 text-white p-2 rounded-full transition duration-200 hover:bg-perf-black hover:text-perf-green"
                                    aria-label="{{ __('messages.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal for image preview -->
        <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
            <span class="absolute top-4 right-4 text-white text-2xl cursor-pointer" id="close-modal">&times;</span>
            <img id="modal-image" src="" alt="Large view" class="max-w-full max-h-full object-contain">
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');
        const productsTable = document.getElementById('products-table');
        const closeNotificationButton = document.getElementById('close-notification');
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
                    productsTable.classList.remove('hidden');
                }
            }, 300);
        });

        async function fetchResults(query) {
            try {
                const response = await fetch(`/api/products/search?query=${encodeURIComponent(query)}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                displayResults(data);
            } catch (error) {
                console.error('Error fetching results:', error);
                searchResults.innerHTML = `<p class="text-center py-4 text-red-500">Error: ${error.message}</p>`;
                searchResults.classList.remove('hidden');
                productsTable.classList.add('hidden');
            }
        }

        function displayResults(products) {
            searchResults.innerHTML = '';
            if (products.length > 0) {
                const table = document.createElement('table');
                table.className = 'min-w-full bg-white';
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">{{ __('messages.name') }}</th>
                            <th class="border px-4 py-2">{{ __('messages.price') }}</th>
                            <th class="border px-4 py-2">{{ __('messages.quantity') }}</th>
                            <th class="border px-4 py-2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${products.map(product => `
                                                                <tr>
                                                                    <td class="border px-4 py-2">${product.name}</td>
                                                                    <td class="border px-4 py-2">${product.price}</td>
                                                                    <td class="border px-4 py-2 ${product.quantity < 10 ? 'text-red-500' : ''}">
                                                                        ${product.quantity}
                                                                        ${product.quantity < 10 ? '<span class="inline-flex items-center bg-red-500 text-white px-1 py-1 rounded-full"><i class="fas fa-exclamation-triangle mr-1"></i> {{ __('messages.warning_stock') }}</span>' : ''}
                                                                    </td>
                                                                    <td class="border px-4 py-2 flex items-center space-x-2">
                                                                        <a href="/products/${product.id}" class="bg-perf-green text-white p-2 rounded-full transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.view') }}">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <a href="/products/${product.id}/edit" class="bg-blue-600 text-white p-2 rounded-full transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.edit') }}">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <form action="/products/${product.id}" method="POST" class="inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="bg-red-600 text-white p-2 rounded-full transition duration-200 hover:bg-perf-black hover:text-perf-green" aria-label="{{ __('messages.delete') }}">
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
                productsTable.classList.add('hidden');
            } else {
                searchResults.innerHTML =
                    `<p class="text-center py-4 text-red-500">{{ __('messages.no_results_found') }}</p>`;
                searchResults.classList.remove('hidden');
                productsTable.classList.add('hidden');
            }
        }

        closeNotificationButton.addEventListener('click', function() {
            document.getElementById('low-stock-notification').classList.add('hidden');
        });
        // Function to open modal and show the clicked image
        function openModal(imageSrc) {
            const modal = document.getElementById('image-modal');
            const modalImage = document.getElementById('modal-image');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
            modal.style.opacity = 1; // Fade in effect

            // Add event listener to close the modal when clicking outside the image
            modal.addEventListener('click', function(event) {
                if (event.target !== modalImage && event.target !== document.getElementById('close-modal')) {
                    closeModal();
                }
            });
        }

        // Function to close modal
        function closeModal() {
            const modal = document.getElementById('image-modal');
            modal.classList.add('hidden');
            modal.style.opacity = 0; // Fade out effect
        }

        // Close modal when clicking on the close button
        document.getElementById('close-modal').addEventListener('click', closeModal);
    </script>
</x-app-layout>
