<x-app-layout>
    <div class="container mx-auto">

        <!-- alert -->
        @if ( session()->has('info') )
            <x-alert-notification type="{{ session('info')['type'] }}" title="{{ session('info')['title'] }}" text="{{ session('info')['text'] }}" />
        @endif

        <!-- modal delete -->
        @can('admin.products.destroy')
            <div id="modalDelete"></div>
        @endcan

        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-lg py-4">Products list</h3>

            @can('admin.products.create')
                <a href="{{ route('admin.products.create') }}" class="p-2 rounded-lg font-medium text-gray-200 bg-blue-700 hover:bg-blue-500">Add New</a>
            @endcan
        </div>
          
        <!-- component -->
        <div class="overflow-hidden overflow-x-auto rounded-lg border border-gray-200 shadow-md mb-5">
            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">ID</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">NAME</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">PRICE</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">FLAVOR</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">TYPE</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">QR</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">CREATED AT</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">

                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $product->id }}</td>
                            <td class="px-6 py-4">{{ $product->name }}</td>
                            <td class="px-6 py-4">{{ $product->price }}</td>
                            <td class="px-6 py-4">{{ $product->flavor->name }}</td>
                            <td class="px-6 py-4">{{ $product->type->name }}</td>
                            <td class="px-6 py-4"><a href="{{ asset('qrcodes/' . $product->qr_code) }}" download><img class="w-[50px] h-[50px]" src="{{ asset('qrcodes/' . $product->qr_code) }}" alt="qrcode"></a></td>
                            <td class="px-6 py-4">{{ $product->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-4">

                                    @can('admin.products.edit')
                                        <a class="p-2 rounded-lg font-medium text-gray-800 hover:text-gray-400 focus:outline-none focus:ring focus:ring-gray-400" href="{{ route('admin.products.edit', $product) }}"><i class="fa-solid fa-edit"></i></a>
                                    @endcan
                                    
                                    @can('admin.products.destroy')
                                        <button data-url="{{ route('admin.products.delete', $product) }}" class="btn-modal-delete p-2 rounded-lg font-medium text-gray-800 hover:text-gray-400 focus:outline-none focus:ring focus:ring-gray-400">
                                            <i class="fa-solid fa-trash pointer-events-none"></i>
                                        </button>
                                    @endcan

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="8" class="px-6 py-4">Sin registros</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- pagination -->
        {{ $products->links() }}
    </div>
</x-app-layout>

<script>
    const btnsModalDelete = document.getElementsByClassName('btn-modal-delete');

    const showModalDelete = ({ target }) => {
        const route = target.getAttribute('data-url');
        
        axios.get( route )
            .then(({ data }) => {
                const modal = document.querySelector( '#modalDelete' );
                modal.innerHTML = data;
            });
    }

    for( let btnModalDelete of btnsModalDelete ) {
        btnModalDelete.addEventListener( 'click', showModalDelete );
    }
</script>