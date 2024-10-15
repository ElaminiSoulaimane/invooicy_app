<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Invoice Item List</h1>
        <a href="{{ route('invoice-items.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-[25px] mb-4">Add New
            Invoice Item</a>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Product/Service</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceItems as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item->product_service->name }}</td>
                        <td class="border px-4 py-2">{{ $item->quantity }}</td>
                        <td class="border px-4 py-2">{{ $item->price }}</td>
                        <td class="border px-4 py-2">{{ $item->quantity * $item->price }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('invoice-items.show', $item->id) }}" class="text-blue-600">View</a>
                            <a href="{{ route('invoice-items.edit', $item->id) }}" class="text-green-600 ml-2">Edit</a>
                            <form action="{{ route('invoice-items.destroy', $item->id) }}" method="POST"
                                class="inline ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
