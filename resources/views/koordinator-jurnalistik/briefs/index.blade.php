@extends('layouts.koordinator-jurnalistik')

@section('title', 'Manajemen Brief Berita')
@section('header', 'Manajemen Brief Berita')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Brief Berita</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola brief berita dari divisi litbang</p>
        </div>
        <a href="{{ route('koordinator-jurnalistik.briefs.create') }}" class="inline-flex items-center px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
            <i class="fas fa-plus mr-2"></i>
            Tambah Brief
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-[#f9b61a]/10 rounded-md flex items-center justify-center">
                        <i class="fas fa-newspaper text-[#f9b61a] text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $totalBriefs }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border border-[#D8C4B6]">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-[#f9b61a]/10 rounded-md flex items-center justify-center">
                        <i class="fas fa-calendar text-[#f9b61a] text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Brief Bulan Ini</p>
                    <p class="text-2xl font-semibold text-[#1b334e]">{{ $briefs->where('tanggal', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all">
        <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('koordinator-jurnalistik.briefs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm" placeholder="Cari judul atau isi brief...">
                </div>
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-[#D8C4B6]/40 rounded-lg shadow-sm text-sm font-medium text-[#1b334e] bg-white hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all">
                        <i class="fas fa-search mr-2"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('koordinator-jurnalistik.briefs.index') }}" class="inline-flex items-center px-4 py-2 border border-[#D8C4B6]/40 rounded-lg shadow-sm text-sm font-medium text-[#1b334e] bg-white hover:bg-[#f9b61a]/10 transition-all">
                        <i class="fas fa-times mr-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Briefs Table -->
    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-lg font-medium text-gray-900">Daftar Brief Berita</h3>
                <span class="text-sm text-gray-500">{{ $briefs->total() }} brief ditemukan</span>
            </div>
        </div>

        @if($briefs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brief</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Referensi</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40" id="briefsTableBody">
                    @foreach($briefs as $brief)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-[#f9b61a]/10 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-newspaper text-[#f9b61a]"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('koordinator-jurnalistik.briefs.show', $brief) }}" class="hover:text-[#f9b61a]">
                                            {{ $brief->judul }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($brief->isi_brief, 80) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                {{ $brief->tanggal ? $brief->tanggal->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($brief->link_referensi)
                                <textarea readonly onclick="this.select()" rows="2" class="w-full bg-gray-50 border border-gray-300 rounded px-2 py-1 text-sm cursor-pointer resize-none" title="Klik untuk menyalin">{{ $brief->link_referensi }}</textarea>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('koordinator-jurnalistik.briefs.show', $brief) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('koordinator-jurnalistik.briefs.edit', $brief) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator-jurnalistik.briefs.destroy', $brief) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus brief ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-[#D8C4B6]/40 sm:px-6">
            {{ $briefs->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada brief berita</h3>
            <p class="text-gray-500 mb-6">Mulai dengan membuat brief berita pertama Anda.</p>
            <a href="{{ route('koordinator-jurnalistik.briefs.create') }}" class="inline-flex items-center px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
                <i class="fas fa-plus mr-2"></i>
                Tambah Brief Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
