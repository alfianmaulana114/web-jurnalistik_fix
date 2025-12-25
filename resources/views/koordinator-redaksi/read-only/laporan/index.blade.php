@extends('layouts.koordinator-redaksi')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container mx-auto px-4 py-6">
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

    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Filter Laporan</h3>
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-6 gap-4">
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

            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <input type="number" id="tahun" name="tahun" min="2020" max="2100" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" />
            </div>

            <div id="tanggal_mulai_container" style="display: none;">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

            <div id="tanggal_selesai_container" style="display: none;">
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>

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

            <div class="md:col-span-2 flex items-end gap-2">
                <button type="button" onclick="applyFilter()" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-filter"></i>
                    Terapkan Filter
                </button>
                <button type="button" onclick="resetFilter()" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-undo"></i>
                    Reset
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <i class="fas fa-wallet text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Laporan Total Saldo</h3>
                            <p class="text-sm text-gray-600">Kas + Pemasukan - Pengeluaran</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Total Saldo:</span>
                        <span class="text-blue-600 font-bold">Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="exportReport('total-saldo')" 
                                class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
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
    const tahunInput = document.getElementById('tahun');

    const now = new Date();
    tahunInput.value = now.getFullYear().toString();

    periodeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            tanggalMulaiContainer.style.display = 'block';
            tanggalSelesaiContainer.style.display = 'block';
        } else {
            tanggalMulaiContainer.style.display = 'none';
            tanggalSelesaiContainer.style.display = 'none';
        }
    });
});

function applyFilter() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    window.location.href = '/koordinator-redaksi/view/laporan' + '?' + params.toString();
}

function resetFilter() {
    document.getElementById('filterForm').reset();
    window.location.href = '/koordinator-redaksi/view/laporan';
}

function viewReport(type) {
    const tahun = document.getElementById('tahun').value;
    const periode = document.getElementById('periode').value;
    const tanggalMulai = document.getElementById('tanggal_mulai')?.value || '';
    const tanggalSelesai = document.getElementById('tanggal_selesai')?.value || '';

    let url = `/koordinator-redaksi/view/laporan?tahun=${tahun}&periode=${periode}`;
    if (periode === 'custom' && tanggalMulai && tanggalSelesai) {
        url += `&tanggal_mulai=${encodeURIComponent(tanggalMulai)}&tanggal_selesai=${encodeURIComponent(tanggalSelesai)}`;
    }
    window.location.href = url;
}

function exportReport(type) {
    const tahun = document.getElementById('tahun').value;
    const periode = document.getElementById('periode').value;
    const tanggalMulai = document.getElementById('tanggal_mulai')?.value || '';
    const tanggalSelesai = document.getElementById('tanggal_selesai')?.value || '';

    let url = `/koordinator-redaksi/view/laporan/export-excel?type=${type}&tahun=${tahun}&periode=${periode}`;
    if (periode === 'custom' && tanggalMulai && tanggalSelesai) {
        url += `&tanggal_mulai=${encodeURIComponent(tanggalMulai)}&tanggal_selesai=${encodeURIComponent(tanggalSelesai)}`;
    }
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `laporan-${type}-${new Date().toISOString().split('T')[0]}.xlsx`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportAllReports() {
    const tahun = document.getElementById('tahun').value;
    const periode = document.getElementById('periode').value;
    const tanggalMulai = document.getElementById('tanggal_mulai')?.value || '';
    const tanggalSelesai = document.getElementById('tanggal_selesai')?.value || '';

    let url = `/koordinator-redaksi/view/laporan/export-excel?type=total-saldo&tahun=${tahun}&periode=${periode}`;
    if (periode === 'custom' && tanggalMulai && tanggalSelesai) {
        url += `&tanggal_mulai=${encodeURIComponent(tanggalMulai)}&tanggal_selesai=${encodeURIComponent(tanggalSelesai)}`;
    }
    
    const link = document.createElement('a');
    link.href = url;
    link.download = `laporan-keuangan-lengkap-${new Date().toISOString().split('T')[0]}.zip`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
