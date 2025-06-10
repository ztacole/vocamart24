@extends('main')

@section('title', 'Manajemen Produk | VocaMart24')

@section('content')
<h2 class="text-2xl font-bold mb-6 ml-4">Manajemen <span class="text-yellow-600">Produk</span></h2>
<div class="flex justify-between mb-6">
    <div class="flex items-center gap-2">
        <a href="#" onclick="openAddModal()" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
            + Produk Baru
        </a>
    </div>
    <div class="flex items-center gap-2">
        <form action="{{ route('admin.product') }}" method="GET">
            @csrf
            <input type="text" name="search" value="{{ request()->query('search') }}" placeholder="Cari produk" class="border border-gray-300 rounded-lg px-2 py-1">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
                Cari
            </button>
        </form>
    </div>
</div>
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-900 bg-white overflow-x-auto">
            <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Gambar Produk</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Nama Produk</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Stok</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Harga</th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b">
                    <td class="px-6 py-4 whitespace-nowrap"><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} Image" class="w-16 h-16 object-cover rounded-lg"></td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="edit-btn bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-stock="{{ $product->stock }}"
                                data-price="{{ $product->price }}"
                                data-image="{{ asset('storage/' . $product->image) }}">
                                Edit
                            </button>
                            <form action="{{ route('admin.product.delete', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Tambah Produk -->
<div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-lg relative">
        <h3 class="text-xl font-bold mb-4">Tambah Produk</h3>
        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block mb-1 font-medium">Nama Produk</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" pattern="[A-Za-z0-9]+" required title="Nama produk hanya boleh mengandung huruf dan angka">
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-medium">Stok</label>
                <input type="number" name="stock" min="1" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-medium">Harga</label>
                <input type="number" name="price" min="1" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Gambar</label>
                <input type="file" name="image" accept="image/*" class="w-full" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Simpan</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Edit Produk -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md relative">
        <h3 class="text-xl font-semibold mb-4">Edit Produk</h3>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="editProductId" name="id">
            <div class="mb-4">
                <label for="editName" class="block font-medium">Nama Produk</label>
                <input type="text" id="editName" readonly name="name" class="w-full border border-gray-300 rounded px-3 py-2" pattern="[A-Za-z0-9]+" required title="Nama produk hanya boleh mengandung huruf dan angka">
            </div>
            <div class="mb-4">
                <label for="editStock" class="block font-medium">Stok</label>
                <input type="number" id="editStock" name="stock" min="1" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label for="editPrice" class="block font-medium">Harga</label>
                <input type="number" id="editPrice" name="price" min="1" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label for="editImage" class="block font-medium">Gambar Baru (opsional)</label>
                <input type="file" id="editImage" name="image" class="w-full" required>
                <img id="editPreviewImage" src="" alt="Preview Gambar" class="mt-2 w-24 h-24 object-cover">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openAddModal() {
        document.getElementById('addProductModal').classList.remove('hidden');
        document.getElementById('addProductModal').classList.add('flex');
    }

    function closeAddModal() {
        document.getElementById('addProductModal').classList.add('hidden');
        document.getElementById('addProductModal').classList.remove('flex');
    }

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('editModal').classList.add('flex');
            // Ambil data produk
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const stock = button.getAttribute('data-stock');
            const price = button.getAttribute('data-price');
            const imageUrl = button.getAttribute('data-image');

            // Isi modal
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editProductId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editStock').value = stock;
            document.getElementById('editPrice').value = price;
            document.getElementById('editPreviewImage').src = imageUrl;

            // Ubah action form
            const form = document.getElementById('editForm');
            form.action = `/product/${id}`;
        });
    });

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
</script>
@endsection