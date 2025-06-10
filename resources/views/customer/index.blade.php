@extends('main')

@section('title', 'Beranda | VocaMart24')

@section('content')
<h2 class="text-2xl font-bold mb-6 ml-4">
    Halo, <span class="text-yellow-600">{{ session('user.name') }}</span>!
    <br>Selamat datang di VocaMart <span class="text-yellow-600">24</span>
</h2>
<div class="flex lg:w-full lg:justify-between sm:flex-row flex-col sm:items-center items-start gap-4 mt-4 sm:mt-0">
    <form action="{{ route('customer.home') }}" method="GET" class="w-full sm:w-auto flex items-center gap-2">
        <h3 class="text-lg py-2">Unit Produksi: </h3>
        <select id="vocationId" name="vocationId" class="block sm:w-auto w-full rounded-lg text-lg border-gray-300 focus:outline-none px-4 py-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200" onchange="this.form.submit()">
            <option selected value="">Semua</option>
            @foreach ($vocations as $vocation)
            <option value="{{ $vocation->id }}" {{ request()->query('vocationId') == $vocation->id ? 'selected' : '' }}>{{ $vocation->name }}</option>
            @endforeach
        </select>
        <input type="hidden" name="search" value="{{ request()->query('search') }}">
    </form>
    <form action="{{ route('customer.home') }}" method="GET" class="w-full sm:w-auto flex items-center gap-2">
        @csrf
        <input type="text" name="search" value="{{ request()->query('search') }}" placeholder="Cari produk" class="border border-gray-300 rounded-lg px-2 py-2 w-full sm:w-auto">
        <input type="hidden" name="vocationId" value="{{ request()->query('vocationId') }}">
        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
            Cari
        </button>
    </form>
</div>
<div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-4">
    @foreach ($products as $product)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} Image" class="w-full aspect-square object-cover">
        <div class="p-4">
            <h3 class="text-md font-normal truncate">{{ $product->name }}</h3>
            <p class="text-sm text-gray-600 truncate">{{ $product->vocational->name }}</p>
            <h3 class="font-bold text-xl mt-2 truncate">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
            <button onclick="document.getElementById('quantityDialog-{{ $product->id }}').classList.remove('hidden');" class="border border-yellow-600 hover:bg-yellow-500 hover:text-white hover:border-yellow-500 duration-300 text-white py-1 text-yellow-600 px-3 rounded mt-6 w-full">+ Keranjang</button>

            <div id="quantityDialog-{{ $product->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Masukkan Jumlah</h3>
                    <form action="{{ route('customer.cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="productId" value="{{ $product->id }}">
                        <h3 class="text-sm text-gray-600">Stok tersedia: {{ $product->stock }}</h3>
                        <input type="number" name="quantity" min="1" value="1" max="{{ $product->stock }}" class="border rounded px-2 py-1 w-full mb-3" required>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('quantityDialog-{{ $product->id }}').classList.add('hidden');" class="bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded">Batal</button>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Tambah ke Keranjang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection