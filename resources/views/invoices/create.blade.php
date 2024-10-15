<!-- resources/views/invoices/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('messages.create_new_invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden   bg-white shadow-sm sm:rounded-[26px]">
                <div class="p-6 bg-white border-b border-x-perf-green">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                        @csrf

                        <div class="mb-4">
                            <label for="client_id" class="block text-sm font-medium text-gray-700">
                                {{ __('messages.client') }}
                            </label>
                            <select name="client_id" id="client_id"
                                class="mt-1 block w-full rounded-[26px] border-perf-green shadow-sm focus:border-perf-green focus:ring-perf-green">
                                <option value="">{{ __('messages.select_client') }}</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="itemsContainer">
                            <!-- Items will be added here dynamically -->
                        </div>

                        {{-- <div class="mt-4 grid grid-cols-3 gap-4">
                            <div>
                                <label for="subtotal" class="block text-sm font-medium text-gray-700">
                                    {{ __('messages.subtotal') }}
                                </label>
                                <p id="subtotal" class="mt-1 text-lg font-bold">0.00 MAD</p>

                            </div>
                            <div>
                                <label for="discount" class="block text-sm font-medium text-gray-700">
                                    {{ __('messages.discount_amount') }}
                                </label>
                                <input type="number" name="discount_amount" id="discount_amount" min="0"
                                    value="0" step="0.01"
                                    class="mt-1 block w-full rounded-[26px] border-gray-300 shadow-sm focus:border-perf-green focus:ring-perf-green"
                                    oninput="calculateTotal()">
                            </div>
                            <div>
                                <label for="totalAfterDiscount" class="block text-sm font-medium text-gray-700">
                                    {{ __('messages.total_after_discount') }}
                                </label>
                                <p id="totalAfterDiscount" class="mt-1 text-lg font-bold">0.00 MAD</p>
                            </div>
                        </div> --}}
                        <div class="mt-4">
                            <button type="button" onclick="addProductRow()"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-[26px] hover:bg-blue-700">
                                {{ __('messages.add_product') }}
                            </button>
                            <button type="button" onclick="addServiceRow()"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-[26px] hover:bg-green-700">
                                {{ __('messages.add_service') }}
                            </button>
                        </div>
                        <div
                            class="mt-8 bg-gradient-to-br from-gray-50 to-white rounded-[26px] shadow-lg p-4 border border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('messages.pricing_summary') }}
                            </h3>
                            <div class="flex gap-4">
                                <!-- Subtotal Section -->
                                <div class="bg-gray-100 p-3 rounded-[26px] flex-1 flex justify-between items-center">
                                    <span class="text-sm text-gray-600">{{ __('messages.subtotal') }}</span>
                                    <span id="subtotal" class="text-sm font-medium text-gray-800">0.00 MAD</span>
                                </div>

                                <!-- Discount Section -->
                                <div class="bg-blue-50 p-3 rounded-[26px] flex-1">
                                    <label for="discount_amount" class="block text-sm text-blue-700 mb-1">
                                        {{ __('messages.discount_amount') }}
                                    </label>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-xs">MAD</span>
                                        </div>
                                        <input type="number" name="discount_amount" id="discount_amount" min="0"
                                            value="0" step="0.01"
                                            class="block w-full pl-10 pr-10 py-2 text-blue-700 placeholder-blue-400 bg-white border border-blue-300 rounded-[26px] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                            placeholder="0.00" oninput="calculateTotal()">
                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                            <label for="discount_type" class="sr-only">Discount Type</label>
                                            <select id="discount_type" name="discount_type"
                                                class="h-full py-0 pl-2 pr-6 border-transparent bg-transparent text-blue-700 text-xs rounded-[26px] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option>Fixed</option>
                                                <option>Percentage</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total After Discount Section -->
                                <div class="bg-green-100 p-3 rounded-[26px] flex-1 flex justify-between items-center">
                                    <span
                                        class="text-sm text-green-700">{{ __('messages.total_after_discount') }}</span>
                                    <span id="totalAfterDiscount" class="text-sm font-medium text-green-700">0.00
                                        MAD</span>
                                </div>
                            </div>
                        </div>



                        <div class="mt-6">
                            <button type="submit"
                                class="px-4 py-2 text-sm   text-perf-black  font-medium border border-black  bg-perf-green rounded-[26px] hover:bg-perf-green">
                                {{ __('messages.create_invoice') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let rowCount = 0;
        let items = [];

        function addProductRow() {
            const container = document.getElementById('itemsContainer');
            const row = document.createElement('div');
            row.classList.add('mb-4', 'p-4', 'border', 'rounded-[26px]', 'border-perf-green');
            row.innerHTML = `
                <input type="hidden" name="items[${rowCount}][type]" value="product">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">@lang('messages.product')</label>
                <select name="items[${rowCount}][id]" onchange="updateQuantityMax(this)" class="mt-1 block w-full rounded-[26px] border-gray-300 shadow-sm focus:border-perf-green focus:ring-perf-green ">
                    <option value="">@lang('messages.select_product')</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-quantity="{{ $product->quantity }}">{{ $product->name }} - {{ $product->price }} MAD (Stock: {{ $product->quantity }})</option>
                    @endforeach
                </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="items[${rowCount}][quantity]" class="mt-1 block w-full rounded-[26px] border-gray-300 shadow-sm focus:border-perf-green focus:ring-perf-green " min="1" value="1" max="1" oninput="validateQuantity(this)">
                        <p class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                    <div>
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="mt-6 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-[26px] hover:bg-red-700">
                            Remove
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(row);
            rowCount++;
        }

        function addServiceRow() {
            const container = document.getElementById('itemsContainer');
            const row = document.createElement('div');
            row.classList.add('mb-4', 'p-4', 'border', 'rounded-[26px]', 'border-perf-green');
            row.innerHTML = `
                <input type="hidden" name="items[${rowCount}][type]" value="service">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                          <label class="block text-sm font-medium text-gray-700">Service</label>
                <select name="items[${rowCount}][id]" class="mt-1 block w-full rounded-[26px] border-gray-300 shadow-sm focus:border-perf-green focus:ring-perf-green ">
                    <option value="">Select Service</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} - {{ $service->price }} MAD</option>
                    @endforeach
                </select>
                    </div>
                    <div>
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="mt-6 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-[26px] hover:bg-red-700">
                            Remove
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(row);
            rowCount++;
        }

        function updateQuantityMax(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const stockQuantity = selectedOption.getAttribute('data-quantity');
            const quantityInput = selectElement.closest('div').nextElementSibling.querySelector('input[type="number"]');
            const messageElement = quantityInput.nextElementSibling;

            quantityInput.max = stockQuantity;
            quantityInput.value = 1; // Reset quantity to 1 when product changes
            messageElement.classList.add('hidden');
            messageElement.textContent = '';
        }

        function validateQuantity(inputElement) {
            const maxQuantity = parseInt(inputElement.max);
            const currentQuantity = parseInt(inputElement.value);
            const messageElement = inputElement.nextElementSibling;

            if (currentQuantity > maxQuantity) {
                messageElement.textContent = `Insufficient stock! Only ${maxQuantity} available.`;
                messageElement.classList.remove('hidden');
                inputElement.value = maxQuantity;
            } else {
                messageElement.classList.add('hidden');
                messageElement.textContent = '';
            }
            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = 0;
            const rows = document.querySelectorAll('#itemsContainer > div');
            rows.forEach(row => {
                const type = row.querySelector('input[type="hidden"]').value;
                const select = row.querySelector('select');
                const selectedOption = select.options[select.selectedIndex];
                let price = 0;
                let quantity = 1;

                if (selectedOption) {
                    price = parseFloat(selectedOption.textContent.match(/(\d+(\.\d{1,2})?) MAD/)[1]) || 0;
                }

                if (type === 'product') {
                    quantity = parseInt(row.querySelector('input[type="number"]').value) || 0;
                }

                subtotal += quantity * price;
            });

            document.getElementById('subtotal').textContent = `${subtotal.toFixed(2)} MAD`;

            const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const discountType = document.getElementById('discount_type')
                .value; // Assuming you have a discount_type dropdown

            // Calculate the discount based on the type (Percentage or Fixed Amount)
            let discount = discountType === 'Percentage' ? (subtotal * discountAmount / 100) : discountAmount;
            let totalAfterDiscount = Math.max(subtotal - discount, 0);

            document.getElementById('totalAfterDiscount').textContent = `${totalAfterDiscount.toFixed(2)} MAD`;
        }

        // Make sure to call calculateTotal whenever there's a change in relevant inputs
        document.getElementById('itemsContainer').addEventListener('change', calculateTotal);
        document.getElementById('discount_amount').addEventListener('input', calculateTotal);
        document.getElementById('discount_type').addEventListener('change',
            calculateTotal); // Added listener for discount type change
    </script>

</x-app-layout>
