@extends('layouts.koordinator-jurnalistik')

@section('title', 'Manajemen Desain Media')
@section('header', 'Desain Media')

@section('content')
<div class="space-y-6">
    <!-- Header with Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Desain Media</h2>
            <p class="mt-1 text-sm text-gray-600">Kelola desain media sederhana</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('koordinator-jurnalistik.designs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1b334e] hover:bg-[#16283e] focus:outline-none">
                <i class="fas fa-plus mr-2"></i>
                Tambah Desain
            </a>
            <button type="button" onclick="openNewsSelectionModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1b334e] hover:bg-[#16283e] focus:outline-none">
                <i class="fas fa-newspaper mr-2"></i>
                Pilih Berita untuk Desain
            </button>
        </div>
    </div>

    <!-- Designs Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-lg font-medium text-gray-900">Daftar Desain</h3>
                <input type="text" id="designsSearchClient" class="w-full sm:w-64 px-3 py-2 border rounded" placeholder="Cari cepat..." />
            </div>
        </div>
        
        @if($designs && $designs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Berita</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="designsTableBody">
                        @foreach($designs as $design)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('koordinator-jurnalistik.designs.show', $design) }}" class="font-medium hover:text-red-600">
                                        {{ Str::limit($design->judul, 60) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php($jenisOptions = \App\Models\Design::getJenisOptions())
                                    @if($design->jenis === 'desain')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $jenisOptions[$design->jenis] ?? ucfirst($design->jenis) }}
                                        </span>
                                    @elseif($design->jenis === 'video')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $jenisOptions[$design->jenis] ?? ucfirst($design->jenis) }}
                                        </span>
                                    @elseif($design->jenis === 'funfact')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $jenisOptions[$design->jenis] ?? ucfirst($design->jenis) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $jenisOptions[$design->jenis] ?? ucfirst($design->jenis) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($design->berita)
                                        <a href="{{ route('koordinator-jurnalistik.news.show', $design->berita) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ Str::limit($design->berita->title ?? $design->berita->judul ?? 'Berita', 40) }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Tidak terkait</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($design->media_url)
                                        <a href="{{ $design->media_url }}" target="_blank" rel="noopener" class="text-blue-600 hover:text-blue-800">
                                            {{ Str::limit($design->media_url, 40) }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ Str::limit($design->catatan, 60) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $design->created_at->format('d M Y') }}</div>
                                    <div class="text-xs">{{ $design->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('koordinator-jurnalistik.designs.show', $design) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('koordinator-jurnalistik.designs.edit', $design) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('koordinator-jurnalistik.designs.destroy', $design) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus desain ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
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
            @if(method_exists($designs, 'links'))
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $designs->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-palette text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada desain</h3>
                <p class="text-gray-500 mb-4">Mulai dengan membuat entri desain pertama Anda.</p>
                <a href="{{ route('koordinator-jurnalistik.designs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1b334e] hover:bg-[#16283e] focus:outline-none">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Desain Pertama
                </a>
            </div>
        @endif
</div>
</div>
<!-- Modal Pilih Berita untuk Desain -->
<div id="newsSelectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[80vh] flex flex-col">
        <!-- Header -->
        <div class="bg-[#1b334e] px-6 py-4 flex-shrink-0">
            <h3 class="text-lg font-semibold text-white">Pilih Berita untuk Desain</h3>
        </div>

        <!-- Pencarian -->
        <div class="px-6 py-4 border-b flex-shrink-0">
            <input type="text" id="newsSearch" placeholder="Cari berita..." class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1b334e]" onkeyup="filterNews()">
        </div>

        <!-- Daftar Berita -->
        <div class="overflow-y-auto flex-1">
            <div class="px-6 py-4 space-y-3">
                @forelse($availableNews as $news)
                    <div class="news-item border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors" data-news-id="{{ $news->id }}" onclick="selectNews(this)">
                        <h4 class="font-semibold text-gray-800">{{ $news->title ?? $news->judul }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit(strip_tags($news->content ?? $news->isi ?? ''), 150) }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ optional($news->created_at)->format('d M Y') }}</span>
                            <span class="text-xs bg-[#1b334e]/10 text-[#1b334e] px-2 py-1 rounded">{{ $news->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-gray-400 text-2xl mb-2"></i>
                        <p class="mt-2">Tidak ada berita yang tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3 flex-shrink-0">
            <button type="button" onclick="closeNewsSelectionModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">Batal</button>
            <button type="button" id="createDesignBtn" onclick="createDesignForSelectedNews()" class="px-4 py-2 bg-[#1b334e] text-white rounded-lg hover:bg-[#16283e] transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>Buat Desain</button>
        </div>
    </div>
</div>

<script>
let selectedNewsId = null;

function openNewsSelectionModal() {
    document.getElementById('newsSelectionModal').classList.remove('hidden');
}

function closeNewsSelectionModal() {
    document.getElementById('newsSelectionModal').classList.add('hidden');
    selectedNewsId = null;
    const btn = document.getElementById('createDesignBtn');
    if (btn) btn.disabled = true;
    document.querySelectorAll('.news-item').forEach(item => item.classList.remove('border-[#1b334e]','bg-[#1b334e]/5'));
}

function filterNews() {
    const term = document.getElementById('newsSearch').value.toLowerCase();
    document.querySelectorAll('.news-item').forEach(item => {
        const title = item.querySelector('h4').textContent.toLowerCase();
        const content = (item.querySelector('p')?.textContent || '').toLowerCase();
        item.style.display = (title.includes(term) || content.includes(term)) ? 'block' : 'none';
    });
}

function selectNews(el) {
    document.querySelectorAll('.news-item').forEach(item => item.classList.remove('border-green-500','bg-green-50'));
    el.classList.add('border-[#1b334e]','bg-[#1b334e]/5');
    selectedNewsId = el.getAttribute('data-news-id');
    const btn = document.getElementById('createDesignBtn');
    if (btn) btn.disabled = !selectedNewsId;
}

function createDesignForSelectedNews() {
    if (!selectedNewsId) return;
    const selectedEl = document.querySelector(`.news-item[data-news-id="${selectedNewsId}"]`);
    const newsTitle = selectedEl ? selectedEl.querySelector('h4').textContent : '';
    const url = `{{ route('koordinator-jurnalistik.designs.create') }}?news_id=${selectedNewsId}&news_title=${encodeURIComponent(newsTitle)}`;
    window.location.href = url;
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('newsSelectionModal');
    if (event.target === modal) {
        closeNewsSelectionModal();
    }
});
</script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('designsSearchClient');
    const tbody = document.getElementById('designsTableBody');
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
