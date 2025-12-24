@extends('layouts.koordinator-humas')

@section('title', 'Daftar Content')
@section('header', 'Manajemen Content')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">Content</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola content caption media kreatif</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('koordinator-humas.contents.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                <i class="fas fa-plus"></i>Buat Content Baru
            </a>
            <button onclick="openDesignSelectionModal()" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                <i class="fas fa-palette"></i>Pilih Desain untuk Caption
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Content</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $contents->total() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-closed-captioning text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Content Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $contents->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Table -->
    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md overflow-hidden">
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Daftar Content</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Semua content caption media kreatif</p>
                </div>
                <input type="text" id="contentsSearchClient" class="w-full sm:w-64 px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] focus:outline-none text-sm" placeholder="Cari cepat..." />
            </div>
        </div>
        @if($contents->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                <thead class="bg-[#f9b61a]/5">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Caption</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desain</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#D8C4B6]/40" id="contentsTableBody">
                    @foreach($contents as $content)
                    <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-[#1b334e]">{{ $content->judul ?? 'Tanpa Judul' }}</div>
                            <div class="text-xs text-gray-600 mt-1">{{ Str::limit($content->caption ?? '', 60) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($content->desain)
                                <div class="text-[#1b334e]">
                                    <i class="fas fa-palette mr-1"></i>{{ $content->desain->judul }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $content->creator->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>{{ $content->created_at->format('d M Y') }}</div>
                            <div class="text-xs">{{ $content->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('koordinator-humas.contents.show', $content) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('koordinator-humas.contents.edit', $content) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('koordinator-humas.contents.destroy', $content) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus content ini?')">
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
            {{ $contents->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-closed-captioning text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-[#1b334e] mb-2">Belum ada content</h3>
            <p class="text-gray-500 mb-6">Buat content baru untuk memulai.</p>
            <a href="{{ route('koordinator-humas.contents.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                <i class="fas fa-plus"></i>Buat Content Pertama
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal untuk memilih desain -->
<div id="designSelectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl border border-[#D8C4B6]/40 shadow-lg w-full max-w-4xl max-h-[80vh] flex flex-col">
        <!-- Modal Header -->
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40 flex-shrink-0">
            <h3 class="text-base font-semibold text-[#1b334e]">Pilih Desain untuk Caption</h3>
            <p class="text-xs text-gray-600 mt-0.5">Menampilkan desain tanpa terkait berita</p>
        </div>
        
        <!-- Search Bar -->
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40 flex-shrink-0">
            <input type="text" id="designSearch" placeholder="Cari desain..." class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] text-sm" onkeyup="filterDesigns()">
        </div>
        
        <!-- Design List -->
        <div class="overflow-y-auto flex-1">
            <div class="px-5 py-4 space-y-2">
                @forelse($availableDesigns as $design)
                <div class="design-item border border-[#D8C4B6]/40 rounded-lg p-3 cursor-pointer hover:bg-[#f9b61a]/5 transition-colors" data-design-id="{{ $design->id }}" onclick="selectDesign(this)">
                    <h4 class="text-sm font-semibold text-[#1b334e]">{{ $design->judul }}</h4>
                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($design->catatan ?? '', 100) }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs text-gray-500">{{ $design->created_at->format('d M Y') }}</span>
                        <span class="text-xs bg-[#1b334e] text-white px-2 py-0.5 rounded">{{ ucfirst($design->jenis ?? 'desain') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-palette text-gray-400 text-2xl mb-2"></i>
                    <p class="mt-2 text-sm">Tidak ada desain yang tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-5 py-4 border-t border-[#D8C4B6]/40 flex justify-end space-x-3 flex-shrink-0">
            <button type="button" onclick="closeDesignSelectionModal()" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">Batal</button>
            <button type="button" id="createContentBtn" onclick="createContentForSelectedDesign()" class="inline-flex items-center gap-1.5 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>Buat Content</button>
        </div>
    </div>
</div>

<script>
let selectedDesignId = null;

function openDesignSelectionModal() {
    document.getElementById('designSelectionModal').classList.remove('hidden');
}

function closeDesignSelectionModal() {
    document.getElementById('designSelectionModal').classList.add('hidden');
    selectedDesignId = null;
    const btn = document.getElementById('createContentBtn');
    if (btn) btn.disabled = true;
    document.querySelectorAll('.design-item').forEach(item => item.classList.remove('border-[#1b334e]','bg-[#1b334e]/5'));
}

function filterDesigns() {
    const term = document.getElementById('designSearch').value.toLowerCase();
    document.querySelectorAll('.design-item').forEach(item => {
        const title = item.querySelector('h4').textContent.toLowerCase();
        const content = (item.querySelector('p')?.textContent || '').toLowerCase();
        item.style.display = (title.includes(term) || content.includes(term)) ? 'block' : 'none';
    });
}

function selectDesign(el) {
    document.querySelectorAll('.design-item').forEach(item => item.classList.remove('border-[#1b334e]','bg-[#1b334e]/5'));
    el.classList.add('border-[#1b334e]','bg-[#1b334e]/5');
    selectedDesignId = el.getAttribute('data-design-id');
    const btn = document.getElementById('createContentBtn');
    if (btn) btn.disabled = !selectedDesignId;
}

function createContentForSelectedDesign() {
    if (!selectedDesignId) return;
    const selectedEl = document.querySelector(`.design-item[data-design-id="${selectedDesignId}"]`);
    const designTitle = selectedEl ? selectedEl.querySelector('h4').textContent : '';
    const url = `{{ route('koordinator-humas.contents.create') }}?design_id=${selectedDesignId}&design_title=${encodeURIComponent(designTitle)}`;
    window.location.href = url;
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('designSelectionModal');
    if (event.target === modal) {
        closeDesignSelectionModal();
    }
});
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('contentsSearchClient');
    const tbody = document.getElementById('contentsTableBody');
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

