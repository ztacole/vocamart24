@foreach ($products as $product)
<div class="bg-white rounded-lg shadow-sm overflow-hidden h-full flex flex-col">
    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} Image" class="w-full aspect-square object-cover">
    <div class="p-4">
        <h3 class="text-md font-normal truncate">{{ $product->name }}</h3>
        <p class="text-sm text-gray-600 truncate">{{ $product->vocational->name }}</p>
        <h3 class="font-bold text-xl mt-2 truncate">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
        <button class="add-to-cart-btn border border-yellow-600 hover:bg-yellow-500 hover:text-white hover:border-yellow-500 duration-300 text-white py-1 text-yellow-600 px-3 rounded mt-6 w-full"
                data-product-id="{{ $product->id }}"
                data-max-stock="{{ $product->stock }}">
            + Keranjang
        </button>
    </div>
</div>
@endforeach

@if($products->isEmpty())
<div class="col-span-full text-center py-8">
    <p class="text-gray-500">Tidak ada produk yang ditemukan</p>
</div>
@endif