@extends('main')

@section('title', 'Dashboard | VocaMart24')

@section('content')
<h2 class="text-2xl font-bold mb-6">
    Halo, <span class="text-yellow-600">{{ session('user.name') }}</span>!
</h2>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

        {{-- Pie Chart --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="text-lg font-semibold mb-4">Produk Terlaris</h3>
            <div class="h-80">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Line Chart --}}
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="text-lg font-semibold mb-4">Trend Penjualan Tahun Ini</h3>
        <div class="h-80">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

{{-- Calendar and Table Section --}}
<div class="flex flex-col lg:flex-row gap-6 mt-6">
    {{-- Calendar --}}
    <div class="bg-white rounded-xl shadow p-5 flex-shrink-0 flex flex-col items-center">
        <h3 class="text-lg font-semibold mb-4 w-full text-align-left">Kalender</h3>
        <div id="datepicker" class="w-full"></div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow p-5 flex-grow">
        <h3 class="text-lg font-semibold mb-4">Transaksi Tanggal: <span id="selected-date">Pilih tanggal</span></h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody id="transaction-data" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Pilih tanggal untuk melihat transaksi</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Penjualan',
                    data: @json($monthlySales),
                    backgroundColor: 'rgba(99, 102, 241, 0.05)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                }
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            }
        });

        // Pie Chart for Top Products
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'pie',
            data: {
                labels: @json(array_column($topProducts, 'name')),
                datasets: [{
                    data: @json(array_column($topProducts, 'total_sold')),
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(220, 38, 38, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(156, 163, 175, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(20, 184, 166, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} terjual (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Initialize datepicker
        flatpickr("#datepicker", {
            inline: true, // <-- ini yang bikin langsung muncul
            dateFormat: "Y-m-d",
            defaultDate: "today",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr) {
                document.getElementById('selected-date').textContent = dateStr;
                fetchTransactionData(dateStr);
            }
        });

        // Function to fetch transaction data
        function fetchTransactionData(date) {
            document.getElementById('selected-date').textContent = date;

            // Example AJAX call - replace with your actual endpoint
            fetch(`/api/transactions?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    updateTransactionTable(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Function to update transaction table
        function updateTransactionTable(transactions) {
            const tbody = document.getElementById('transaction-data');

            if (transactions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada transaksi pada tanggal ini
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            transactions.forEach((transaction, index) => {
                html += `
                    <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} border-b">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.product_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${transaction.quantity}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp ${transaction.total.toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }
    });
</script>
@endpush
@endsection