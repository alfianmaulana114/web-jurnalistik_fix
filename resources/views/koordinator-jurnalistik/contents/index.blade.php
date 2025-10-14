@extends('layouts.koordinator-jurnalistik')

@section('title', 'Daftar Caption')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Daftar Caption</h3>
            <a href="{{ route('koordinator-jurnalistik.contents.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i> Buat Caption Baru
            </a>
        </div>
        
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative">
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                        <span class="text-2xl">&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            @endif

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
                            <option value="caption_berita_redaksi">Caption Berita Redaksi</option>
                            <option value="caption_media_kreatif">Caption Media Kreatif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="statusFilter" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="sedang_direview">Sedang Direview</option>
                            <option value="dipublikasi">Dipublikasi</option>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                                            @if($content->brief)
                                                <div class="text-sm text-gray-500">Brief: {{ $content->brief->judul }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ App\Models\Content::getCaptionTypes()[$content->jenis_konten] ?? $content->jenis_konten }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($content->isCaptionMediaKreatif())
                                                @if($content->media_type)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ $content->getMediaTypeLabel() }}
                                                    </span>
                                                @endif
                                                @if($content->media_path)
                                                    <div class="text-xs text-green-600 mt-1">
                                                        <i class="fas fa-file"></i> File tersedia
                                                    </div>
                                                @endif
                                            @elseif($content->isCaptionBerita() && $content->berita_referensi)
                                                <div class="text-xs text-blue-600">
                                                    <i class="fas fa-link"></i> Ada referensi
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($content->status == 'dipublikasi') bg-green-100 text-green-800
                                                @elseif($content->status == 'sedang_direview') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $content->getStatusLabel() }}
                                            </span>
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