<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tambahkan Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-yellow-500 text-white p-4 flex flex-col justify-between fixed top-0 left-0 h-screen transform -translate-x-full transition-transform duration-300 z-40">
            <!-- Toggle Button inside sidebar, positioned to the right -->
            <button id="sidebarToggle" class="absolute right-0 top-4 transform translate-x-full bg-yellow-500 text-white p-2 rounded-r-lg hover:bg-yellow-600 focus:outline-none">
                <i id="sidebarIcon" class="fas fa-bars text-xl"></i>
            </button>

            <div class="mt-8"> <!-- Added margin top to accommodate toggle button -->
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

        <!-- Overlay -->
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30"></div>

        <!-- Main Content -->
        <main id="mainContent" class="flex-1 bg-gray-100 p-6 overflow-y-auto transition-all duration-300">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarIcon = document.getElementById('sidebarIcon');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');

            // Check localStorage for sidebar state
            const isSidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
            
            // Initialize sidebar state
            if (isSidebarOpen) {
                openSidebar();
            } else {
                closeSidebar();
            }

            // Toggle sidebar - improved click area
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event bubbling
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            // Close sidebar when clicking on overlay
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Close sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (!sidebar.contains(e.target)) {
                    closeSidebar();
                }
            });

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                sidebarIcon.classList.replace('fa-bars', 'fa-times');
                mainContent.classList.add('ml-64');
                localStorage.setItem('sidebarOpen', 'true');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                sidebarIcon.classList.replace('fa-times', 'fa-bars');
                mainContent.classList.remove('ml-64');
                localStorage.setItem('sidebarOpen', 'false');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>