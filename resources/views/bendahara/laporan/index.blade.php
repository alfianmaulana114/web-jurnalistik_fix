@extends('layouts.bendahara')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-gray-600 mt-1">Kelola dan lihat berbagai laporan keuangan organisasi</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="exportAllReports()" 
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <i class="fas fa-file-excel mr-2"></i>Export Semua
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Laporan</h3>
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Periode -->
            <div>
                <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select id="periode" name="periode" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="bulan_lalu">Bulan Lalu</option>
                    <option value="3_bulan">3 Bulan Terakhir</option>
                    <option value="6_bulan">6 Bulan Terakhir</option>
                    <option value="tahun_ini">Tahun Ini</option>
                    <option value="tahun_lalu">Tahun Lalu</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            <!-- Bulan -->
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select id="bulan" name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <input type="number" id="tahun" name="tahun" min="2020" max="2100" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" />
            </div>

            <!-- Tanggal Mulai -->
            <div id="tanggal_mulai_container" style="display: none;">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <!-- Tanggal Selesai -->
            <div id="tanggal_selesai_container" style="display: none;">
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <!-- Divisi -->
            <div>
                <label for="divisi" class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                <select id="divisi" name="divisi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Semua Divisi</option>
                    <option value="jurnalistik">Jurnalistik</option>
                    <option value="kreatif">Kreatif</option>
                    <option value="media_sosial">Media Sosial</option>
                    <option value="humas">Humas</option>
                    <option value="bendahara">Bendahara</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Laporan Kas Anggota -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Laporan Kas Anggota</h3>
                        <p class="text-sm text-gray-600">Status pembayaran kas anggota</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Total Terkumpul:</span>
                    <span class="text-green-600 font-bold">Rp 15.750.000</span>
                </div>
            <div class="flex space-x-2">
                    <button onclick="viewReport('kas-anggota')" 
                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </button>
                    <button onclick="exportReport('kas-anggota')" 
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Laporan Pemasukan -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Laporan Pemasukan</h3>
                        <p class="text-sm text-gray-600">Rincian semua pemasukan</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Total Pemasukan:</span>
                    <span class="text-green-600 font-bold">Rp 18.250.000</span>
                </div>
                <div class="flex space-x-2">
                    <button onclick="viewReport('pemasukan')" 
                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </button>
                    <button onclick="exportReport('pemasukan')" 
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Laporan Pengeluaran -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Laporan Pengeluaran</h3>
                        <p class="text-sm text-gray-600">Rincian semua pengeluaran</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Total Pengeluaran:</span>
                    <span class="text-red-600 font-bold">Rp 12.500.000</span>
                </div>
                <div class="flex space-x-2">
                    <button onclick="viewReport('pengeluaran')" 
                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </button>
                    <button onclick="exportReport('pengeluaran')" 
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                </div>
            </div>
        </div>

        

        <!-- Laporan Arus Kas -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <i class="fas fa-exchange-alt text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Laporan Arus Kas</h3>
                        <p class="text-sm text-gray-600">Pergerakan kas masuk dan keluar</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Kas Akhir:</span>
                    <span class="text-green-600 font-bold">Rp 8.750.000</span>
                </div>
                <div class="flex space-x-2">
                    <button onclick="viewReport('arus-kas')" 
                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        <i class="fas fa-eye mr-1"></i>Lihat
                    </button>
                    <button onclick="exportReport('arus-kas')" 
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Ringkasan Keuangan</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">Rp 18.25M</div>
                <div class="text-sm text-gray-600">Total Pemasukan</div>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <div class="text-2xl font-bold text-red-600">Rp 12.5M</div>
                <div class="text-sm text-gray-600">Total Pengeluaran</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">Rp 5.75M</div>
                <div class="text-sm text-gray-600">Saldo Bersih</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">85%</div>
                <div class="text-sm text-gray-600">Tingkat Koleksi Kas</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periode');
    const tanggalMulaiContainer = document.getElementById('tanggal_mulai_container');
    const tanggalSelesaiContainer = document.getElementById('tanggal_selesai_container');
    const bulanSelect = document.getElementById('bulan');
    const tahunInput = document.getElementById('tahun');

    // Set default bulan/tahun sekarang
    const now = new Date();
    bulanSelect.value = (now.getMonth() + 1).toString();
    tahunInput.value = now.getFullYear().toString();

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

function viewReport(type) {
    // Get current filter values
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;

    let url = '';
    if (type === 'kas-anggota') {
        url = `/bendahara/laporan/kas-anggota?bulan=${bulan}&tahun=${tahun}`;
    } else if (type === 'pemasukan') {
        url = `/bendahara/pemasukan?bulan=${bulan}&tahun=${tahun}`;
    } else if (type === 'pengeluaran') {
        url = `/bendahara/pengeluaran?bulan=${bulan}&tahun=${tahun}`;
    } else {
        url = `/bendahara/laporan/keuangan?bulan=${bulan}&tahun=${tahun}`;
    }

    window.location.href = url;
}

function exportReport(type) {
    // Get current filter values
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;

    // Build export URL (controller exportExcel expects type, bulan, tahun)
    let url = `/bendahara/laporan/export-excel?type=${type}&bulan=${bulan}&tahun=${tahun}`;
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = `laporan-${type}-${new Date().toISOString().split('T')[0]}.xlsx`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportAllReports() {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    
    // Optional: implement export-all zip on backend jika dibutuhkan
    let url = `/bendahara/laporan/export-excel?type=kas-anggota&bulan=${bulan}&tahun=${tahun}`;
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `laporan-keuangan-lengkap-${new Date().toISOString().split('T')[0]}.zip`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection