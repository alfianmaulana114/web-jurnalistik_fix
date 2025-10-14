@extends('layouts.bendahara')

@section('title', 'Daftar Kas Anggota')
@section('header', 'Manajemen Kas Anggota')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Daftar Kas Anggota</h3>
            <a href="{{ route('bendahara.kas-anggota.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kas Anggota
            </a>
        </div>
        
        <div class="p-6">
            <!-- Filter Section -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <button type="button" onclick="toggleFilter()" class="text-green-600 hover:text-green-800 font-medium">
                    <i class="fas fa-filter mr-2"></i>Filter Kas Anggota
                </button>
                <div id="filterSection" class="hidden mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                        <select id="divisiFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Divisi</option>
                            <option value="redaksi">Redaksi</option>
                            <option value="litbang">Litbang</option>
                            <option value="humas">Humas</option>
                            <option value="media_kreatif">Media Kreatif</option>
                            <option value="pengurus">Pengurus</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="statusFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="belum_lunas">Belum Lunas</option>
                            <option value="nunggak">Nunggak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                        <select id="periodeFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Periode</option>
                            @foreach($periodeOptions as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select id="tahunFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunOptions as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                        <input type="text" id="searchFilter" placeholder="Nama anggota..." class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="mt-4 hidden" id="filterButtons">
                    <button type="button" onclick="applyFilter()" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm mr-2 hover:bg-green-700">
                        Terapkan Filter
                    </button>
                    <button type="button" onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-green-600 font-medium">Lunas</p>
                            <p class="text-xl font-bold text-green-700">{{ $stats['lunas'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-yellow-600 font-medium">Belum Lunas</p>
                            <p class="text-xl font-bold text-yellow-700">{{ $stats['belum_lunas'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-red-600 font-medium">Nunggak</p>
                            <p class="text-xl font-bold text-red-700">{{ $stats['nunggak'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <i class="fas fa-coins text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Total Terkumpul</p>
                            <p class="text-lg font-bold text-blue-700">Rp {{ number_format($stats['total_terkumpul'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan/Tahun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sudah Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Belum Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kasAnggota as $kas)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration + ($kasAnggota->currentPage() - 1) * $kasAnggota->perPage() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="p-2 rounded-full bg-gray-100 text-gray-600 mr-3">
                                            <i class="fas fa-user text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $kas->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $kas->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $kas->user->division)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst($kas->periode) }} {{ $kas->tahun }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                    Rp {{ number_format($kas->jumlah_terbayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                    Rp {{ number_format(\App\Models\KasSetting::getValue('jumlah_kas_anggota', 15000) - $kas->jumlah_terbayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($kas->status_pembayaran == 'lunas') bg-green-100 text-green-800
                                        @elseif($kas->status_pembayaran == 'belum_lunas') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $kas->status_pembayaran)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('bendahara.kas-anggota.show', $kas) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('bendahara.kas-anggota.edit', $kas) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('bendahara.kas-anggota.destroy', $kas) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus data kas anggota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-6xl text-gray-400 mb-4"></i>
                                        <h5 class="text-lg font-medium text-gray-900 mb-2">Belum ada data kas anggota</h5>
                                        <p class="text-gray-500 mb-4">Mulai dengan menambahkan data kas anggota pertama.</p>
                                        <a href="{{ route('bendahara.kas-anggota.create') }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                                            <i class="fas fa-plus mr-2"></i> Tambah Kas Anggota
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kasAnggota->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $kasAnggota->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFilter() {
    const filterSection = document.getElementById('filterSection');
    const filterButtons = document.getElementById('filterButtons');
    
    if (filterSection.classList.contains('hidden')) {
        filterSection.classList.remove('hidden');
        filterButtons.classList.remove('hidden');
    } else {
        filterSection.classList.add('hidden');
        filterButtons.classList.add('hidden');
    }
}

function applyFilter() {
    const divisi = document.getElementById('divisiFilter').value;
    const status = document.getElementById('statusFilter').value;
    const periode = document.getElementById('periodeFilter').value;
    const tahun = document.getElementById('tahunFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    const params = new URLSearchParams();
    if (divisi) params.append('divisi', divisi);
    if (status) params.append('status', status);
    if (periode) params.append('periode', periode);
    if (tahun) params.append('tahun', tahun);
    if (search) params.append('search', search);
    
    const url = new URL(window.location.href);
    url.search = params.toString();
    window.location.href = url.toString();
}

function resetFilter() {
    document.getElementById('divisiFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('periodeFilter').value = '';
    document.getElementById('tahunFilter').value = '';
    document.getElementById('searchFilter').value = '';
    
    const url = new URL(window.location.href);
    url.search = '';
    window.location.href = url.toString();
}

// Set current filter values from URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('divisi')) {
        document.getElementById('divisiFilter').value = urlParams.get('divisi');
        toggleFilter();
    }
    if (urlParams.get('status')) {
        document.getElementById('statusFilter').value = urlParams.get('status');
        toggleFilter();
    }
    if (urlParams.get('periode')) {
        document.getElementById('periodeFilter').value = urlParams.get('periode');
        toggleFilter();
    }
    if (urlParams.get('tahun')) {
        document.getElementById('tahunFilter').value = urlParams.get('tahun');
        toggleFilter();
    }
    if (urlParams.get('search')) {
        document.getElementById('searchFilter').value = urlParams.get('search');
        toggleFilter();
    }
});
</script>
@endpush
@endsection