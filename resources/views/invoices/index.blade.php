<x-app-layout>
    <div class="container mx-auto py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6" @if (app()->getLocale() == 'ar') dir="rtl" @endif>
            <h1 class="text-3xl font-bold text-gray-800">{{ __('messages.invoice_management') }}</h1>
            <div class="@if (app()->getLocale() == 'ar') space-x-reverse @endif space-x-2">
                <a href="{{ route('invoices.create') }}"
                    class="bg-perf-green text-perf-black px-6 py-2 rounded-full hover:bg-perf-green_hover transition duration-300">
                    <i
                        class="fas fa-plus @if (app()->getLocale() == 'ar') ml-2 @else mr-2 @endif"></i>{{ __('messages.new_invoice') }}
                </a>
                <a href="{{ route('payments.index') }}"
                    class="bg-perf-black text-white border border-perf-green px-6 py-2 rounded-full hover:bg-perf-green_hover  transition duration-300">
                    <i
                        class="fas fa-money-bill-wave @if (app()->getLocale() == 'ar') ml-2 @else mr-2 @endif"></i>{{ __('messages.all_payments') }}
                </a>
            </div>
        </div>



        <!-- Search and Filter Section -->
        <div class="mb-6 bg-white p-6 rounded-[26px] border border-perf-green shadow">
            <form action="{{ route('invoices.index') }}" method="GET"
                class="flex flex-wrap items-center space-y-2 sm:space-y-0">
                <input type="text" name="search" placeholder="{{ __('messages.search_by_invoice_number') }}"
                    class="rounded-[26px] border border-perf-green px-3 py-2 mr-2 mb-2 sm:mb-0 flex-grow sm:flex-grow-0 focus:outline-none ">
                <select name="status"
                    class="border border-gray-300 rounded-[26px] px-3 py-2 mr-2 mb-2 sm:mb-0 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('messages.all_statuses') }}</option>
                    <option value="paid">{{ __('messages.paid') }}</option>
                    <option value="partially_paid">{{ __('messages.partially_paid') }}</option>
                    <option value="unpaid">{{ __('messages.unpaid') }}</option>
                </select>
                <button type="submit"
                    class="bg-gray-500 text-white px-4 py-2 rounded-[26px] hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-search mr-2"></i>{{ __('messages.search') }}
                </button>
            </form>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white rounded-[26px] border border-perf-green shadow overflow-x-auto">
            <table class="min-w-full divide-y">
                <thead class="text-perf-black bg-perf-green">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.invoice_number') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.client') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.total_amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.discount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.paid_amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.remaining_balance') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.payments') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->client->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($invoice->total_amount, 2) }} MAD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($invoice->discount_amount > 0)
                                    <span class="text-green-600">{{ number_format($invoice->discount_amount, 2) }}
                                        MAD</span>
                                @else
                                    {{ __('messages.no') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($invoice->payments->sum('amount'), 2) }} MAD</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($invoice->remaining_balance == 0)
                                    <span class="text-green-600 font-semibold">{{ __('messages.completed') }}</span>
                                @else
                                    {{ number_format($invoice->remaining_balance, 2) }} MAD
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->payments->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($invoice->total_amount == 0)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">N/A</span>
                                @elseif($invoice->remaining_balance <= 0 && $invoice->payments->sum('amount') > 0)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('messages.paid') }}</span>
                                @elseif($invoice->payments->sum('amount') > 0)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __('messages.partially_paid') }}</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('messages.unpaid') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                <a href="{{ route('invoices.show', $invoice->id) }}"
                                    class="text-blue-600 hover:underline">{{ __('messages.view') }}</a>
                                <a href="{{ route('invoices.edit', $invoice->id) }}"
                                    class="text-green-600 hover:underline">{{ __('messages.edit') }}</a>
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:underline">{{ __('messages.delete') }}</button>
                                </form>
                                @if ($invoice->remaining_balance > 0)
                                    <a href="{{ route('invoices.payments.create', $invoice->id) }}"
                                        class="text-indigo-600 hover:underline">{{ __('messages.create_payment') }}</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">{{ __('messages.no_invoices_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $invoices->links() }}
        </div>
    </div>
</x-app-layout>
