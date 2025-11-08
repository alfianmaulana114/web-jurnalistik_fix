@extends('layouts.koordinator-jurnalistik')

@section('title', 'Daftar Caption')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Daftar Caption</h3>
            <div class="flex space-x-3">
                <a href="{{ route('koordinator-jurnalistik.contents.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Buat Caption Baru
                </a>
                <button onclick="openNewsSelectionModal()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                    <i class="fas fa-newspaper mr-2"></i> Pilih Berita untuk Caption
                </button>
                <button onclick="openDesignSelectionModal()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                    <i class="fas fa-palette mr-2"></i> Pilih Desain untuk Caption
                </button>
            </div>
        </div>
        
        <div class="p-6">

            <!-- Filter Section -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <button type="button" onclick="toggleFilter()" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-filter mr-2"></i>Filter Caption
                </button>
                <div id="filterSection" class="hidden mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Caption</label>
                        <select id="jenisFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="caption_berita">Caption Berita Redaksi</option>
                            <option value="caption_media_kreatif">Caption Media Kreatif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Media</label>
                        <select id="mediaFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Tipe</option>
                            <option value="image">Gambar</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 hidden" id="filterButtons">
                    <button type="button" onclick="applyFilter()" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm mr-2 hover:bg-blue-700">
                        Terapkan Filter
                    </button>
                    <button type="button" onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                        Reset Filter
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Caption</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Caption</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Berita/Design</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($contents as $content)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration + ($contents->currentPage() - 1) * $contents->perPage() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $content->judul }}</div>
                                            
                                            <!-- News Reference -->
                                            @if($content->isCaptionBerita() && $content->berita()->exists())
                                                <div class="text-sm text-blue-600">
                                                    <i class="fas fa-newspaper mr-1"></i> Berita: {{ $content->berita->title }}
                                                </div>
                                            @endif
                                            
                                            <!-- Design Reference -->
                                            @if(($content->isCaptionMediaKreatif() || $content->isCaptionDesain()) && $content->desain()->exists())
                                                <div class="text-sm text-purple-600">
                                                    <i class="fas fa-palette mr-1"></i> Desain: {{ $content->desain->judul }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ App\Models\Content::getCaptionTypes()[$content->jenis_konten] ?? $content->jenis_konten }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($content->isCaptionBerita() && $content->berita()->exists())
                                                <div class="text-sm text-blue-600">
                                                    <i class="fas fa-newspaper mr-1"></i> {{ $content->berita->title }}
                                                </div>
                                            @elseif(($content->isCaptionMediaKreatif() || $content->isCaptionDesain()) && $content->desain()->exists())
                                                <div class="text-sm text-purple-600">
                                                    <i class="fas fa-palette mr-1"></i> {{ $content->desain->judul }}
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $content->creator->name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>{{ $content->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $content->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('koordinator-jurnalistik.contents.show', $content) }}" 
                                                   class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('koordinator-jurnalistik.contents.edit', $content) }}" 
                                                   class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('koordinator-jurnalistik.contents.destroy', $content) }}" 
                                                      method="POST" class="inline" 
                                                      onsubmit="return confirm('Yakin ingin menghapus caption ini?')">
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
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                                                <h5 class="text-lg font-medium text-gray-900 mb-2">Belum ada caption</h5>
                                                <p class="text-gray-500 mb-4">Mulai dengan membuat caption pertama Anda.</p>
                                                <a href="{{ route('koordinator-jurnalistik.contents.create') }}" 
                                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                                                    <i class="fas fa-plus mr-2"></i> Buat Caption Baru
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($contents->hasPages())
                        <div class="flex justify-center mt-6">
                            {{ $contents->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk memilih berita -->
<div id="newsSelectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[80vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-blue-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Pilih Berita untuk Caption</h3>
        </div>
        
        <!-- Search Bar -->
        <div class="px-6 py-4 border-b">
            <input 
                type="text" 
                id="newsSearch" 
                placeholder="Cari berita..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                onkeyup="filterNews()"
            >
        </div>
        
        <!-- News List -->
        <div class="overflow-y-auto max-h-96">
            <div class="px-6 py-4 space-y-3">
                @forelse($availableNews as $news)
                <div class="news-item border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors"
                     data-news-id="{{ $news->id }}"
                     onclick="selectNews(this)">
                    <h4 class="font-semibold text-gray-800">{{ $news->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit(strip_tags($news->content), 150) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">{{ $news->created_at->format('d M Y') }}</span>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $news->category->name ?? 'Uncategorized' }}</span>
                            </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2">Tidak ada berita yang tersedia untuk caption</p>
                    <p class="text-sm">Semua berita sudah memiliki caption</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3">
            <button type="button" 
                    onclick="closeNewsSelectionModal()" 
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </button>
            <button type="button" 
                    id="createCaptionBtn"
                    onclick="createCaptionForSelectedNews()" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                Buat Caption
            </button>
        </div>
    </div>
</div>

<!-- Modal untuk memilih desain (hanya desain tanpa berita) -->
<div id="designSelectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[80vh] flex flex-col">
        <!-- Modal Header -->
        <div class="bg-purple-600 px-6 py-4 flex-shrink-0">
            <h3 class="text-lg font-semibold text-white">Pilih Desain untuk Caption</h3>
            <p class="text-xs text-purple-100">Menampilkan desain tanpa terkait berita</p>
        </div>
        
        <!-- Search Bar -->
        <div class="px-6 py-4 border-b flex-shrink-0">
            <input 
                type="text" 
                id="designSearch" 
                placeholder="Cari desain..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                onkeyup="filterDesigns()"
            >
        </div>
        
        <!-- Design List -->
        <div class="overflow-y-auto flex-1">
            <div class="px-6 py-4 space-y-3">
                @forelse($availableDesigns as $design)
                <div class="design-item border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition-colors"
                     data-design-id="{{ $design->id }}"
                     data-design-title="{{ $design->judul }}"
                     onclick="selectDesign(this)">
                    <h4 class="font-semibold text-gray-800">{{ $design->judul }}</h4>
                    <div class="text-sm text-gray-600 mt-1">Jenis: {{ ucfirst($design->jenis ?? 'desain') }}</div>
                    @if($design->catatan)
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit(strip_tags($design->catatan), 120) }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs text-gray-500">{{ $design->created_at->format('d M Y') }}</span>
                        @if($design->media_url)
                            <a href="{{ $design->media_url }}" target="_blank" class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Lihat Media</a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2">Tidak ada desain tersedia</p>
                    <p class="text-sm">Semua desain terkait berita</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3 flex-shrink-0">
            <button type="button" 
                    onclick="closeDesignSelectionModal()" 
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </button>
            <button type="button" 
                    id="createDesignCaptionBtn"
                    onclick="createCaptionForSelectedDesign()" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                Buat Caption
            </button>
        </div>
    </div>
</div>
@push('scripts')
<script>
let selectedNewsId = null;
let selectedDesignId = null;

function openNewsSelectionModal() {
    document.getElementById('newsSelectionModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeNewsSelectionModal() {
    document.getElementById('newsSelectionModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    selectedNewsId = null;
    document.getElementById('createCaptionBtn').disabled = true;
    
    // Reset selection
    document.querySelectorAll('.news-item').forEach(item => {
        item.classList.remove('border-blue-500', 'bg-blue-50');
    });
}

function filterNews() {
    const searchTerm = document.getElementById('newsSearch').value.toLowerCase();
    const newsItems = document.querySelectorAll('.news-item');
    
    newsItems.forEach(item => {
        const title = item.querySelector('h4').textContent.toLowerCase();
        const content = item.querySelector('p').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || content.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function selectNews(element) {
    // Remove previous selection
    document.querySelectorAll('.news-item').forEach(item => {
        item.classList.remove('border-blue-500', 'bg-blue-50');
    });
    
    // Add selection to clicked item
    element.classList.add('border-blue-500', 'bg-blue-50');
    selectedNewsId = element.getAttribute('data-news-id');
    document.getElementById('createCaptionBtn').disabled = false;
}

function createCaptionForSelectedNews() {
    if (selectedNewsId) {
        // Get the selected news title
        const selectedNews = document.querySelector(`.news-item[data-news-id="${selectedNewsId}"]`);
        const newsTitle = selectedNews.querySelector('h4').textContent;
        
        // Redirect to create page with news_id and news_title parameters
        window.location.href = `{{ route('koordinator-jurnalistik.contents.create') }}?news_id=${selectedNewsId}&news_title=${encodeURIComponent(newsTitle)}`;
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('newsSelectionModal');
    if (event.target === modal) {
        closeNewsSelectionModal();
    }
});

function openDesignSelectionModal() {
    document.getElementById('designSelectionModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeDesignSelectionModal() {
    document.getElementById('designSelectionModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    selectedDesignId = null;
    document.getElementById('createDesignCaptionBtn').disabled = true;
    // Reset selection
    document.querySelectorAll('.design-item').forEach(item => {
        item.classList.remove('border-purple-500', 'bg-purple-50');
    });
}

function filterDesigns() {
    const searchTerm = document.getElementById('designSearch').value.toLowerCase();
    const designItems = document.querySelectorAll('.design-item');
    
    designItems.forEach(item => {
        const title = item.querySelector('h4').textContent.toLowerCase();
        const extraText = item.querySelector('p')?.textContent.toLowerCase() || '';
        if (title.includes(searchTerm) || extraText.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function selectDesign(element) {
    // Remove previous selection
    document.querySelectorAll('.design-item').forEach(item => {
        item.classList.remove('border-purple-500', 'bg-purple-50');
    });
    
    element.classList.add('border-purple-500', 'bg-purple-50');
    selectedDesignId = element.getAttribute('data-design-id');
    document.getElementById('createDesignCaptionBtn').disabled = false;
}

function createCaptionForSelectedDesign() {
    if (selectedDesignId) {
        const selectedItem = document.querySelector(`.design-item[data-design-id="${selectedDesignId}"]`);
        const designTitle = selectedItem.getAttribute('data-design-title');
        const url = `{{ route('koordinator-jurnalistik.contents.create') }}?design_id=${selectedDesignId}&design_title=${encodeURIComponent(designTitle)}`;
        window.location.href = url;
    }
}

function toggleFilter() {
    const filterSection = document.getElementById('filterSection');
    const filterButtons = document.getElementById('filterButtons');
    filterSection.classList.toggle('hidden');
    filterButtons.classList.toggle('hidden');
}

function applyFilter() {
    const jenis = document.getElementById('jenisFilter').value;
    const status = document.getElementById('statusFilter').value;
    const media = document.getElementById('mediaFilter').value;
    
    const params = new URLSearchParams();
    if (jenis) params.append('jenis_konten', jenis);
    if (status) params.append('status', status);
    if (media) params.append('media_type', media);
    
    const url = new URL(window.location.href);
    url.search = params.toString();
    window.location.href = url.toString();
}

function resetFilter() {
    document.getElementById('jenisFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('mediaFilter').value = '';
    
    const url = new URL(window.location.href);
    url.search = '';
    window.location.href = url.toString();
}
</script>
@endpush
@endsection