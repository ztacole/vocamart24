<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-yellow-500 text-white p-4 flex flex-col justify-between fixed top-0 left-0 h-screen">
            <div>
                <div class="text-3xl font-bold mb-6 text-center">
                    <span class="text-black">VOCAMART</span><span class="text-white">24</span>
                </div>

                @php $role = session('user')->role_id ?? null; @endphp

                @if ($role == 1)
                <!-- Customer -->
                <x-sidebar-item link="{{ route('customer.home') }}" label="Beranda" icon="ic-home" />
                <x-sidebar-item link="{{ route('customer.cart') }}" label="Keranjang" icon="ic-cart" />
                <x-sidebar-item link="{{ route('customer.history') }}" label="Riwayat" icon="ic-history" />
                @elseif (in_array($role, [2,3,4,5,6]))
                <!-- Admin -->
                <x-sidebar-item link="{{ route('admin.dashboard') }}" label="Dashboard" icon="ic-dashboard" />
                <x-sidebar-item link="{{ route('admin.product') }}" label="Produk" icon="ic-product" />
                <x-sidebar-item link="{{ route('admin.report') }}" label="Laporan" icon="ic-report" />
                @endif
            </div>

            <form action="{{ route('logout') }}" method="GET">
                @csrf
                <button class="flex items-center gap-2 bg-white text-yellow-600 px-4 py-2 rounded hover:bg-yellow-100">
                    Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-gray-100 p-6 ml-64 overflow-y-auto">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>

</html>
