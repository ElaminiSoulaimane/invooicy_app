<x-app-layout>
    <div class="container mx-auto py-8" @if (app()->getLocale() == 'ar') dir="rtl" @endif>
        <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ __('messages.invoice') }} #{{ $invoice->invoice_number }}
        </h1>

        <!-- Client and Invoice Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Client Information -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ __('messages.client_information') }}</h3>
                <p><strong>{{ __('messages.name') }}:</strong> {{ $invoice->client->name }}</p>
                <p><strong>{{ __('messages.email') }}:</strong> {{ $invoice->client->email }}</p>
                <p><strong>{{ __('messages.phone') }}:</strong> {{ $invoice->client->phone }}</p>
            </div>
            <!-- Invoice Details -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ __('messages.invoice_details') }}</h3>
                <p><strong>{{ __('messages.date') }}:</strong> {{ $invoice->created_at->format('F d, Y') }}</p>
                <p><strong>{{ __('messages.time') }}:</strong> {{ $invoice->created_at->format('h:i A') }}</p>
                <p><strong>{{ __('messages.status') }}:</strong> {{ ucfirst($invoice->status) }}</p>
            </div>
        </div>

        <!-- Invoice Items Table -->
        <div class="bg-white shadow-md rounded-lg overflow-x-auto mb-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.item') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.img') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.quantity') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.unit_price') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($invoice->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                {{ $item->product ? $item->product->name : $item->service->name }}
                                @if (!$item->product)
                                    <span class="text-green-500">({{ __('messages.service') }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($item->product && $item->product->image)
                                    <img src="{{ Storage::url($item->product ? $item->product->image : $item->service->image) }}"
                                        alt="{{ $item->product ? $item->product->name : $item->service->name }}"
                                        class="w-16 h-16 object-cover rounded-full cursor-pointer">
                                @else
                                 <img src="{{ asset('images/Dufult_product.png') }}" alt="No Image"
                                    class="w-16 h-16 object-cover rounded-lg ">
                                @endif
                            </td>



                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $unitPrice =
                                        $item->unit_price ??
                                        ($item->product
                                            ? $item->product->price
                                            : ($item->service
                                                ? $item->service->price
                                                : 0));
                                @endphp
                                {{ number_format($unitPrice, 2) }} MAD
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $total = $unitPrice * ($item->quantity ?? 0);
                                @endphp
                                {{ number_format($total, 2) }} MAD
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Invoice Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div></div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <table class="w-full">
                    <tr>
                        <th class="text-left text-gray-600">{{ __('messages.subtotal') }}:</th>
                        <td class="text-right">{{ number_format($invoice->total_amount ?? 0, 2) }} MAD</td>
                    </tr>
                    <tr>
                        <th class="text-left text-gray-600">{{ __('messages.discount') }}:</th>
                        <td class="text-right">{{ number_format($invoice->discount_amount ?? 0, 2) }} MAD</td>
                    </tr>
                    <tr>
                        <th class="text-left text-gray-600">{{ __('messages.total_due') }}:</th>
                        <td class="text-right">{{ number_format($invoice->discounted_total ?? 0, 2) }} MAD</td>
                    </tr>
                    <tr>
                        <th class="text-left text-gray-600">{{ __('messages.paid_amount') }}:</th>
                        <td class="text-right">{{ number_format($invoice->payments->sum('amount') ?? 0, 2) }} MAD</td>
                    </tr>
                    <tr>
                        <th class="text-left text-gray-600">{{ __('messages.remaining_balance') }}:</th>
                        <td class="text-right">{{ number_format($invoice->remaining_balance ?? 0, 2) }} MAD</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Payments Section -->
        <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4">{{ __('messages.payments') }}</h3>
        @if ($invoice->payments->isNotEmpty())
            <div class="bg-white shadow-md rounded-lg overflow-x-auto mt-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($invoice->payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y h:i A') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ number_format($payment->amount ?? 0, 2) }} MAD
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 mt-4">{{ __('messages.no_payments_recorded') }}</p>
        @endif

        @if ($invoice->remaining_balance > 0)
            <a href="{{ route('invoices.payments.create', $invoice->id) }}"
                class="bg-purple-600 text-white px-4 py-2 rounded-full hover:bg-purple-700 transition duration-300 mt-4 inline-block">
                {{ __('messages.create_payment') }}
            </a>
        @endif
    </div>
</x-app-layout>
