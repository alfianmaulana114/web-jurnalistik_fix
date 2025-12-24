@extends('layouts.koordinator-redaksi')

@section('title', 'Manajemen Funfact')
@section('header', 'Manajemen Funfact')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Funfact</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola funfact untuk konten menarik</p>
        </div>
        <a href="{{ route('koordinator-redaksi.funfacts.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <i class="fas fa-plus"></i>
            Tambah Funfact
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Funfact</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $totalFunfacts }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-lightbulb text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Funfact Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $funfacts->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Funfacts Table -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Daftar Funfact</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Semua funfact yang tersedia</p>
                </div>
                <span class="text-xs text-gray-500">{{ $funfacts->total() }} funfact ditemukan</span>
            </div>
        </div>

        @if($funfacts->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Funfact</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Referensi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40">
                    @foreach($funfacts as $funfact)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-[#f9b61a]/10 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-lightbulb text-[#f9b61a]"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-[#1b334e]">
                                        <a href="{{ route('koordinator-redaksi.funfacts.show', $funfact) }}" class="hover:text-[#f9b61a]">
                                            {{ $funfact->judul }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">{{ Str::limit($funfact->isi, 80) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($funfact->link_referensi)
                                @php($links = $funfact->getLinksArray())
                                @if(count($links) > 0)
                                    <div class="space-y-1">
                                        @foreach(array_slice($links, 0, 2) as $link)
                                            <a href="{{ $link }}" target="_blank" class="block text-[#1b334e] hover:text-[#f9b61a] truncate max-w-xs" title="{{ $link }}">
                                                <i class="fas fa-external-link-alt mr-1"></i>{{ Str::limit($link, 40) }}
                                            </a>
                                        @endforeach
                                        @if(count($links) > 2)
                                            <span class="text-xs text-gray-500">+{{ count($links) - 2 }} link lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $funfact->creator->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                {{ $funfact->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('koordinator-redaksi.funfacts.show', $funfact) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('koordinator-redaksi.funfacts.edit', $funfact) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator-redaksi.funfacts.destroy', $funfact) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus funfact ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-all" title="Hapus">
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
        @if($funfacts->hasPages())
        <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
            {{ $funfacts->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <i class="fas fa-lightbulb text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada funfact</h3>
            <p class="text-gray-500 mb-6">Mulai dengan membuat funfact pertama Anda.</p>
            <a href="{{ route('koordinator-redaksi.funfacts.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                <i class="fas fa-plus"></i>
                Tambah Funfact Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

