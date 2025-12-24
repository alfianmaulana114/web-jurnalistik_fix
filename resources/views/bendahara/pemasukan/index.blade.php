@extends('layouts.bendahara')

@section('title', 'Manajemen Pemasukan')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pemasukan</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola pemasukan keuangan UKM Jurnalistik</p>
        </div>
        <a href="{{ route('bendahara.pemasukan.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <i class="fas fa-plus"></i>Tambah Pemasukan
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Pemasukan</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($statistics['total'], 0, ',', '.') }}</p>
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
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $statistics['verified'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $statistics['pending'] }}</p>
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
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($statistics['this_month'], 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar-month text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md p-5">
        <form method="GET" action="{{ route('bendahara.pemasukan.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="kategori" class="block text-xs font-medium text-gray-700 mb-1.5">Kategori</label>
                <select name="kategori" id="kategori" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                    <option value="">Semua Kategori</option>
                    @foreach(App\Models\Pemasukan::getKategoriOptions() as $key => $value)
                        <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
                    <option value="">Semua Status</option>
                    @foreach(App\Models\Pemasukan::getStatusOptions() as $key => $value)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
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
            <div>
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1.5">Pencarian</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Cari deskripsi atau sumber..." 
                       class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:outline-none focus:ring-[#f9b61a] focus:border-[#f9b61a]">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-search"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route('bendahara.pemasukan.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                    <i class="fas fa-refresh"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40">
                    @forelse($pemasukan as $index => $item)
                        <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pemasukan->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $item->deskripsi }}">
                                    {{ $item->deskripsi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-[#1b334e] text-white">
                                    {{ $item->getKategoriLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $item->sumber }}">
                                    {{ $item->sumber }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $item->status === 'verified' ? 'bg-green-50 text-green-700' : 'bg-[#f9b61a]/10 text-[#1b334e]' }}">
                                    {{ $item->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->createdBy->name ?? 'Sistem' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('bendahara.pemasukan.show', $item) }}" 
                                       class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bendahara.pemasukan.edit', $item) }}" 
                                       class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($item->status === 'pending')
                                        <form action="{{ route('bendahara.pemasukan.verify', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Verifikasi"
                                                    onclick="return confirm('Apakah Anda yakin ingin memverifikasi pemasukan ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('bendahara.pemasukan.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all" title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pemasukan ini?')">
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
                                    <h5 class="text-lg font-medium text-gray-900 mb-2">Belum ada pemasukan</h5>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan pemasukan pertama Anda.</p>
                                    <a href="{{ route('bendahara.pemasukan.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                                        <i class="fas fa-plus"></i>Tambah Pemasukan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pemasukan->hasPages())
            <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
                {{ $pemasukan->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection