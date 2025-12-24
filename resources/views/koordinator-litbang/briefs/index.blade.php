@extends('layouts.koordinator-litbang')

@section('title', 'Manajemen Brief')
@section('header', 'Manajemen Brief Divisi Litbang')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">Brief</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola brief divisi litbang</p>
        </div>
        <a href="{{ route('koordinator-litbang.briefs.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
            <i class="fas fa-plus"></i>Tambah Brief
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $totalBriefs }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Brief Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $briefs->where('tanggal', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Briefs Table -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Daftar Brief</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Semua brief divisi litbang</p>
                </div>
                <input type="text" id="briefsSearchClient" class="w-full sm:w-64 px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] focus:outline-none text-sm" placeholder="Cari cepat..." />
            </div>
        </div>
        @if($briefs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brief</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referensi</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40" id="briefsTableBody">
                    @foreach($briefs as $brief)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-[#1b334e]">
                                <a href="{{ route('koordinator-litbang.briefs.show', $brief) }}" class="hover:text-[#f9b61a]">{{ $brief->judul }}</a>
                            </div>
                            <div class="text-xs text-gray-600 mt-1">{{ Str::limit($brief->isi_brief, 80) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $brief->tanggal ? $brief->tanggal->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($brief->link_referensi)
                                <textarea readonly onclick="this.select()" rows="2" class="w-full bg-gray-50 border border-[#D8C4B6]/40 rounded-lg px-2 py-1 text-xs cursor-pointer resize-none">{{ $brief->link_referensi }}</textarea>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('koordinator-litbang.briefs.show', $brief) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('koordinator-litbang.briefs.edit', $brief) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator-litbang.briefs.destroy', $brief) }}" method="POST" class="inline" onsubmit="return confirm('Hapus brief ini?')">
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
        <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
            {{ $briefs->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-file-alt text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-[#1b334e] mb-2">Belum ada brief</h3>
            <p class="text-gray-500 mb-6">Buat brief baru untuk memulai.</p>
            <a href="{{ route('koordinator-litbang.briefs.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                <i class="fas fa-plus"></i>Tambah Brief Pertama
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('briefsSearchClient');
    const tbody = document.getElementById('briefsTableBody');
    if (!input || !tbody) return;
    input.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        Array.from(tbody.querySelectorAll('tr')).forEach(function(row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>
@endpush
@endsection