@extends('layouts.bendahara')

@section('title', 'Manajemen Pengeluaran')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengeluaran</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola semua pengeluaran organisasi</p>
        </div>
        <a href="{{ route('bendahara.pengeluaran.create') }}" 
           class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <i class="fas fa-plus"></i>Tambah Pengeluaran
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($statistics['total'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Terverifikasi</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($statistics['verified'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($statistics['pending'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($statistics['current_month'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md p-5">
        <form method="GET" action="{{ route('bendahara.pengeluaran.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Kategori Filter -->
            <div>
                <label for="kategori" class="block text-xs font-medium text-gray-700 mb-1.5">Kategori</label>
                <select name="kategori" id="kategori" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                    <option value="">Semua Kategori</option>
                    <option value="operasional" {{ request('kategori') === 'operasional' ? 'selected' : '' }}>Operasional</option>
                    <option value="acara" {{ request('kategori') === 'acara' ? 'selected' : '' }}>Acara</option>
                    <option value="peralatan" {{ request('kategori') === 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                    <option value="konsumsi" {{ request('kategori') === 'konsumsi' ? 'selected' : '' }}>Konsumsi</option>
                    <option value="transport" {{ request('kategori') === 'transport' ? 'selected' : '' }}>Transport</option>
                    <option value="lainnya" {{ request('kategori') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label for="bulan" class="block text-xs font-medium text-gray-700 mb-1.5">Bulan</label>
                <select name="bulan" id="bulan" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <label for="tahun" class="block text-xs font-medium text-gray-700 mb-1.5">Tahun</label>
                <select name="tahun" id="tahun" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= date('Y') - 3; $year--)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1.5">Cari</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="Deskripsi, kode transaksi..."
                       class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
            </div>

            <!-- Filter Button -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-search"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('bendahara.pengeluaran.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-undo"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Pengeluaran Table -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40">
                    @forelse($pengeluaran as $index => $item)
                        <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengeluaran->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ optional($item->tanggal)->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    <p class="font-medium">{{ Str::limit($item->deskripsi, 50) }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->kode_transaksi }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-[#1b334e] text-white">
                                    {{ $item->getKategoriLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1b334e]">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->penerima }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ in_array($item->status, ['approved','paid']) ? 'bg-green-50 text-green-700' : ($item->status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-[#f9b61a]/10 text-[#1b334e]') }}">
                                    {{ $item->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ optional($item->creator)->name ?? 'Sistem' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('bendahara.pengeluaran.show', $item) }}" 
                                       class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bendahara.pengeluaran.edit', $item) }}" 
                                       class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($item->status === 'pending')
                                        <form action="{{ route('bendahara.pengeluaran.approve', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" 
                                                    title="Setujui"
                                                    onclick="return confirm('Setujui pengeluaran ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('bendahara.pengeluaran.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all" 
                                                title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?')">
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
                                    <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                                    <h5 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengeluaran</h5>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan pengeluaran pertama Anda.</p>
                                    <a href="{{ route('bendahara.pengeluaran.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                                        <i class="fas fa-plus"></i>Tambah Pengeluaran Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pengeluaran->hasPages())
            <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
                {{ $pengeluaran->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Form hanya submit saat tombol Filter ditekan (tidak auto-submit)
document.addEventListener('DOMContentLoaded', function() {
    // Tidak ada auto-submit, form hanya submit saat tombol Filter ditekan
});
</script>
@endsection