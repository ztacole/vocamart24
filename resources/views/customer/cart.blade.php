@extends('main')

@section('title', 'Keranjang | VocaMart24')

@section('content')
<h2 class="text-2xl font-bold mb-6 ml-4">Keranjang</h2>

@if($cartItems->isEmpty())
<div class="bg-white p-6 rounded-lg text-center">
    <h3 class="text-2xl font-bold mb">Wah, keranjang belanjamu kosong</h3>
    <p class="text-gray-600 text-lg mb-6">Yuk, isi dengan produk-produk yang kamu inginkan!</p>
    <a href="{{ route('customer.home') }}" class="bg-yellow-500 text-white px-10 font-bold py-3 rounded-xl hover:bg-yellow-600 duration-300">Mulai Belanja</a>
</div>
@else
<div class="space-y-4 pb-40">
    @foreach($cartItems as $cart)
    <div class="flex items-center justify-between bg-white shadow rounded-lg p-4">
        <div class="flex items-center gap-4">
            <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}" class="w-16 h-16 object-cover rounded">
            <div>
                <h4 class="font-semibold">{{ $cart->product->name }}</h4>
                <p class="text-sm text-gray-500">{{ $cart->product->category }}</p>
                <p class="text-yellow-600 font-semibold">Rp. {{ number_format($cart->product->price, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <h3 class="text-sm text-gray-600 pr-4">Stok tersedia: {{ $cart->product->stock }}</h3>        
            <form action="{{ route('customer.cart.decrease') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="cartItemId" value="{{ $cart->id }}">
                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">−</button>
            </form>
            <span class="font-semibold px-6">{{ $cart->quantity }}</span>
            <form action="{{ route('customer.cart.increase') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="cartItemId" value="{{ $cart->id }}">
                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">+</button>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Transaksi Ringkasan -->
    <div id="transactionSummary" class="fixed bottom-0 left-0 right-0 w-full z-50 bg-white shadow-md p-4 transition-all duration-300">
        <h3 class="font-bold mb-2">Transaksi</h3>
        @php $total = 0; @endphp
        @foreach($cartItems as $cart)
        @php
        $subtotal = $cart->product->price * $cart->quantity;
        $total += $subtotal;
        @endphp
        <div class="flex justify-between">
            <span>{{ $cart->quantity }} {{ $cart->product->name }}</span>
            <span>Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach
        <hr class="my-2">
        <div class="flex justify-between font-bold">
            <span>Total:</span>
            <span>Rp. {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        <form action="{{ route('customer.cart.checkout') }}" method="GET" class="mt-4 text-center">
            @csrf
            <button type="submit" name="checkout" class="bg-yellow-500 text-white font-semibold px-6 py-2 rounded hover:bg-yellow-600 w-1/2">Checkout</button>
        </form>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const transactionSummary = document.getElementById('transactionSummary');
    
    // Fungsi untuk mengecek state sidebar
    function checkSidebarState() {
        if (sidebar.classList.contains('-translate-x-full')) {
            // Sidebar tertutup - tampilkan ringkasan
            transactionSummary.classList.remove('hidden');
        } else {
            // Sidebar terbuka - sembunyikan ringkasan
            transactionSummary.classList.add('hidden');
        }
    }
    
    // Jalankan saat pertama kali load
    checkSidebarState();
    
    // Observasi perubahan class pada sidebar
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                checkSidebarState();
            }
        });
    });
    
    // Mulai observasi
    observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>
@endsection