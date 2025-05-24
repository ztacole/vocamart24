@extends('main')

@section('title', 'Dashboard | VocaMart24')

@section('content')
<h2 class="text-2xl font-bold mb-6">
    Halo, <span class="text-yellow-600">{{ session('user.name') }}</span>!
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- Card: Pemasukan --}}
    <div class="bg-white rounded-xl shadow p-5">
        <div class="text-sm text-gray-500">Pemasukan</div>
        <div class="text-2xl font-semibold text-green-600 mt-1">Rp {{ number_format($income, 0, ',', '.') }}</div>
    </div>

    {{-- Card: Jumlah Produk --}}
    <div class="bg-white rounded-xl shadow p-5">
        <div class="text-sm text-gray-500">Jumlah Produk</div>
        <div class="text-2xl font-semibold text-indigo-600 mt-1">{{ $productCount }}</div>
    </div>
</div>
@endsection