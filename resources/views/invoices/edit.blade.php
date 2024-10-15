<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('messages.edit_invoice', ['number' => $invoice->invoice_number]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('invoices.update', $invoice) }}">
                        @csrf
                        @method('PUT')

                        <!-- Client Selection -->
                        <div class="mb-6">
                            <label for="client_id"
                                class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.client') }}</label>
                            <select name="client_id" id="client_id"
                                class="form-select block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                disabled required>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Invoice Items -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('messages.invoice_items') }}</h3>
                            <div id="invoice-items" class="space-y-4">
                                @foreach ($invoice->items as $index => $item)
                                    <div class="invoice-item bg-gray-50 p-4 rounded-lg shadow">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }}</label>
                                                <select name="items[{{ $index }}][type]"
                                                    class="form-select block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    required>
                                                    <option value="product"
                                                        {{ $item->type == 'product' ? 'selected' : '' }}>
                                                        {{ __('messages.product') }}</option>
                                                    <option value="service"
                                                        {{ $item->type == 'service' ? 'selected' : '' }}>
                                                        {{ __('messages.service') }}</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.item') }}</label>
                                                <select name="items[{{ $index }}][id]"
                                                    class="form-select block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    required>
                                                    @if ($item->type == 'product')
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }} -
                                                                {{ number_format($product->price, 2) }} MAD
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($services as $service)
                                                            <option value="{{ $service->id }}"
                                                                {{ $item->service_id == $service->id ? 'selected' : '' }}>
                                                                {{ $service->name }} -
                                                                {{ number_format($service->price, 2) }} MAD
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.quantity') }}</label>
                                                <input type="number" name="items[{{ $index }}][quantity]"
                                                    value="{{ $item->quantity }}" min="1"
                                                    class="form-input block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    required>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button"
                                                    class="remove-item w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4  rounded-[26px] transition duration-300 ease-in-out focus:outline-none focus:shadow-outline">
                                                    {{ __('messages.remove') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-item"
                                class="mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4  rounded-[26px] transition duration-300 ease-in-out focus:outline-none focus:shadow-outline">
                                {{ __('messages.add_item') }}
                            </button>
                        </div>

                        <!-- Discount Amount -->
                        <div class="mb-6">
                            <label for="discount_amount"
                                class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.discount_amount') }}</label>
                            <input type="number" name="discount_amount" id="discount_amount"
                                value="{{ $invoice->discount_amount }}" step="0.01" min="0"
                                class="form-input block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4  rounded-[26px] transition duration-300 ease-in-out focus:outline-none focus:shadow-outline">
                                {{ __('messages.update_invoice') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('messages.confirm_delete') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('messages.confirm_delete_message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmDelete"
                        class="w-full inline-flex justify-center  rounded-[26px] border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.delete') }}
                    </button>
                    <button type="button" id="cancelDelete"
                        class="mt-3 w-full inline-flex justify-center  rounded-[26px] border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('messages.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemButton = document.getElementById('add-item');
            const invoiceItems = document.getElementById('invoice-items');
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteButton = document.getElementById('confirmDelete');
            const cancelDeleteButton = document.getElementById('cancelDelete');
            let itemToDelete = null;
            let itemIndex = {{ count($invoice->items) }};

            addItemButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.className = 'invoice-item bg-gray-50 p-4 rounded-lg shadow';
                newItem.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.type') }}</label>
                            <select name="items[${itemIndex}][type]" class="form-select block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="product">{{ __('messages.product') }}</option>
                                <option value="service">{{ __('messages.service') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.item') }}</label>
                            <select name="items[${itemIndex}][id]" class="form-select block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">{{ __('messages.select_product_service') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.quantity') }}</label>
                            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="form-input block w-full  rounded-[26px] border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-item w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4  rounded-[26px] transition duration-300 ease-in-out focus:outline-none focus:shadow-outline">
                                {{ __('messages.remove') }}
                            </button>
                        </div>
                    </div>
                `;
                invoiceItems.appendChild(newItem);
                itemIndex++;

                // Add event listener for type change
                const typeSelect = newItem.querySelector('select[name$="[type]"]');
                typeSelect.addEventListener('change', updateItemOptions);
            });

            invoiceItems.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-item')) {
                    itemToDelete = event.target.closest('.invoice-item');
                    deleteModal.classList.remove('hidden');
                }
            });

            confirmDeleteButton.addEventListener('click', function() {
                if (itemToDelete) {
                    itemToDelete.remove();
                    itemToDelete = null;
                }
                deleteModal.classList.add('hidden');
            });

            cancelDeleteButton.addEventListener('click', function() {
                itemToDelete = null;
                deleteModal.classList.add('hidden');
            });

            function updateItemOptions(event) {
                const typeSelect = event.target;
                const idSelect = typeSelect.closest('.grid').querySelector('select[name$="[id]"]');
                const type = typeSelect.value;

                // Clear current options
                idSelect.innerHTML = `<option value="">{{ __('messages.select') }} ${type}</option>`;


                // Add new options based on type
                if (type === 'product') {
                    @foreach ($products as $product)
                        idSelect.innerHTML +=
                            `<option value="{{ $product->id }}">{{ $product->name }} - {{ number_format($product->price, 2) }} MAD</option>`;
                    @endforeach
                } else {
                    @foreach ($services as $service)
                        idSelect.innerHTML +=
                            `<option value="{{ $service->id }}">{{ $service->name }} - {{ number_format($service->price, 2) }} MAD</option>`;
                    @endforeach
                }
            }
        });
    </script>
</x-app-layout>
