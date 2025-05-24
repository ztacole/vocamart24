@extends('main')

@section('title', 'Laporan | VocaMart24')

@section('content')
<h2 class="text-2xl font-bold mb-6">Laporan <span class="text-yellow-600">Penjualan</span></h2>
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-900 bg-white overflow-x-auto">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Waktu</th>
                    <th scope="col" class="px-6 py-3">Nama Produk</th>
                    <th scope="col" class="px-6 py-3">Harga Produk</th>
                    <th scope="col" class="px-6 py-3">Jumlah</th>
                    <th scope="col" class="px-6 py-3">Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $index => $record)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b">
                        <td class="px-6 py-4">{{ $record->transactionHeader->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">{{ $record->transactionHeader->created_at->format('H:i:s') }}</td>
                        <td class="px-6 py-4">{{ $record->product->name }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($record->product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ $record->quantity }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($record->product->price * $record->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
