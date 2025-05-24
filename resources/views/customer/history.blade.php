@extends('main')

@section('title', 'Riwayat | VocaMart24')

@section('content')
<h2 class="text-2xl font-bold mb-6">Riwayat Transaksi</h2>
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-900 bg-white overflow-x-auto">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Jumlah Produk</th>
                    <th scope="col" class="px-6 py-3">Total Pembayaran</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $index => $transaction)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b">
                    <td class="px-6 py-4">{{ $transaction->created_at->locale('id')->translatedFormat('l, d F Y H:i:s') }}</td>
                    <td class="px-6 py-4">{{ $transaction->status }}</td>
                    <td class="px-6 py-4">{{ $transaction->details->count() }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($transaction->details->sum(function ($detail) {
                            return $detail->product->price * $detail->quantity;
                        }), 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <button type="button" class="text-blue-600 hover:text-blue-900" onclick="document.getElementById('detailDialog-{{ $transaction->id }}').classList.remove('hidden');">
                            Lihat Detail
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Detail Transaksi -->
        @foreach ($histories as $transaction)
        <div id="detailDialog-{{ $transaction->id }}" class="fixed z-50 inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-[800px]">
                    <h3 class="font-bold text-xl mb-4">Detail Transaksi</h3>
                    <table class="w-full text-sm text-left text-gray-900">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                            <tr>
                                <th class="px-4 py-2">Nama Produk</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Harga Satuan</th>
                                <th class="px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->details as $detail)
                            <tr>
                                <td class="px-4 py-2">{{ $detail->product->name }}</td>
                                <td class="px-4 py-2">{{ $detail->quantity }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($detail->product->price * $detail->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="mt-6 w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg"
                        onclick="document.getElementById('detailDialog-{{ $transaction->id }}').classList.add('hidden');">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection