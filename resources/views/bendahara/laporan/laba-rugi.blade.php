@extends('layouts.bendahara')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Laba Rugi</h1>
            <p class="text-gray-600 mt-1">Analisis keuangan dan performa organisasi per periode</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('bendahara.laporan.index') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button onclick="exportReport()" 
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
            <button onclick="printReport()" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-print mr-2"></i>Print
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Periode -->
            <div>
                <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select id="periode" name="periode" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="bulan_lalu" {{ request('periode') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                    <option value="3_bulan" {{ request('periode') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="6_bulan" {{ request('periode') == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="tahun_lalu" {{ request('periode') == 'tahun_lalu' ? 'selected' : '' }}>Tahun Lalu</option>
                    <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            <!-- Tanggal Mulai (for custom period) -->
            <div id="tanggal_mulai_container" style="display: {{ request('periode') == 'custom' ? 'block' : 'none' }};">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <!-- Tanggal Selesai (for custom period) -->
            <div id="tanggal_selesai_container" style="display: {{ request('periode') == 'custom' ? 'block' : 'none' }};">
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <!-- Perbandingan -->
            <div>
                <label for="perbandingan" class="block text-sm font-medium text-gray-700 mb-1">Perbandingan</label>
                <select id="perbandingan" name="perbandingan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Tanpa Perbandingan</option>
                    <option value="periode_sebelumnya" {{ request('perbandingan') == 'periode_sebelumnya' ? 'selected' : '' }}>Periode Sebelumnya</option>
                    <option value="tahun_lalu" {{ request('perbandingan') == 'tahun_lalu' ? 'selected' : '' }}>Tahun Lalu</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pemasukan</p>
                    <p class="text-2xl font-bold text-green-600">Rp 18.250.000</p>
                    <p class="text-xs text-green-500 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+12.5% dari periode sebelumnya
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-600">Rp 12.500.000</p>
                    <p class="text-xs text-red-500 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+8.3% dari periode sebelumnya
                    </p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Laba Bersih</p>
                    <p class="text-2xl font-bold text-blue-600">Rp 5.750.000</p>
                    <p class="text-xs text-green-500 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+18.2% dari periode sebelumnya
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Margin Laba</p>
                    <p class="text-2xl font-bold text-purple-600">31.5%</p>
                    <p class="text-xs text-green-500 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+4.2% dari periode sebelumnya
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-percentage text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Report -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-700">Laporan Laba Rugi</h3>
            <p class="text-sm text-gray-600 mt-1">Periode: {{ date('F Y') }}</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-6">
                <!-- PEMASUKAN -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-plus-circle text-green-600 mr-2"></i>PEMASUKAN
                    </h4>
                    <div class="ml-6 space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Kas Anggota</span>
                            <span class="font-medium text-gray-900">Rp 15.750.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Donasi</span>
                            <span class="font-medium text-gray-900">Rp 1.500.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Sponsor Acara</span>
                            <span class="font-medium text-gray-900">Rp 1.000.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-semibold text-gray-800">TOTAL PEMASUKAN</span>
                            <span class="font-bold text-green-600 text-lg">Rp 18.250.000</span>
                        </div>
                    </div>
                </div>

                <!-- PENGELUARAN -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-minus-circle text-red-600 mr-2"></i>PENGELUARAN
                    </h4>
                    <div class="ml-6 space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Operasional</span>
                            <span class="font-medium text-gray-900">Rp 4.500.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Acara</span>
                            <span class="font-medium text-gray-900">Rp 3.200.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Peralatan</span>
                            <span class="font-medium text-gray-900">Rp 2.800.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Konsumsi</span>
                            <span class="font-medium text-gray-900">Rp 1.500.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-700">Transport</span>
                            <span class="font-medium text-gray-900">Rp 500.000</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-semibold text-gray-800">TOTAL PENGELUARAN</span>
                            <span class="font-bold text-red-600 text-lg">Rp 12.500.000</span>
                        </div>
                    </div>
                </div>

                <!-- LABA RUGI -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">LABA BERSIH</span>
                        <span class="text-2xl font-bold text-blue-600">Rp 5.750.000</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <span>Margin Laba: </span>
                        <span class="font-semibold text-purple-600">31.5%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Tren Laba Rugi (6 Bulan Terakhir)</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            <!-- Sample Chart Bars -->
            @php
                $months = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $profits = [3200000, 4100000, 3800000, 4500000, 5200000, 5750000];
                $maxProfit = max($profits);
            @endphp
            
            @foreach($months as $index => $month)
                @php
                    $height = ($profits[$index] / $maxProfit) * 100;
                    $isPositive = $profits[$index] > 0;
                @endphp
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-{{ $isPositive ? 'green' : 'red' }}-500 rounded-t" 
                         style="height: {{ $height }}%"></div>
                    <div class="text-xs text-gray-600 mt-2">{{ $month }}</div>
                    <div class="text-xs font-medium text-gray-800">
                        {{ number_format($profits[$index] / 1000000, 1) }}M
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Breakdown by Category -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pemasukan by Category -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Pemasukan per Kategori</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Kas Anggota</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">Rp 15.75M</div>
                        <div class="text-xs text-gray-500">86.3%</div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Donasi</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">Rp 1.5M</div>
                        <div class="text-xs text-gray-500">8.2%</div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Sponsor</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">Rp 1M</div>
                        <div class="text-xs text-gray-500">5.5%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran by Category -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Pengeluaran per Kategori</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Operasional</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">Rp 4.5M</div>
                        <div class="text-xs text-gray-500">36%</div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-orange-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Acara</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">Rp 3.2M</div>
                        <div class="text-xs text-gray-500">25.6%</div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Peralatan</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">Rp 2.8M</div>
                        <div class="text-xs text-gray-500">22.4%</div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-indigo-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700">Lainnya</span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">Rp 2M</div>
                        <div class="text-xs text-gray-500">16%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periode');
    const tanggalMulaiContainer = document.getElementById('tanggal_mulai_container');
    const tanggalSelesaiContainer = document.getElementById('tanggal_selesai_container');

    // Toggle custom date inputs
    periodeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            tanggalMulaiContainer.style.display = 'block';
            tanggalSelesaiContainer.style.display = 'block';
        } else {
            tanggalMulaiContainer.style.display = 'none';
            tanggalSelesaiContainer.style.display = 'none';
        }
    });

    // Auto-submit filter form
    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Here you would typically submit the form or make an AJAX request
            console.log('Filter changed:', this.name, this.value);
        });
    });
});

function exportReport() {
    // Get current filter values
    const params = new URLSearchParams();
    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input');
    
    filterInputs.forEach(input => {
        if (input.value) {
            params.append(input.name, input.value);
        }
    });
    
    // Create export URL
    const url = `/bendahara/laporan/laba-rugi/export?${params.toString()}`;
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = `laporan-laba-rugi-${new Date().toISOString().split('T')[0]}.xlsx`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printReport() {
    window.print();
}
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        -webkit-print-color-adjust: exact;
    }
    
    .bg-white {
        background-color: white !important;
    }
    
    .shadow-sm {
        box-shadow: none !important;
    }
}
</style>
@endsection