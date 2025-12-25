@extends($layout ?? 'layouts.koordinator-humas')

@section('title', 'Daftar Caption')
@section('header', 'Daftar Caption (Read-Only)')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Read-Only Banner --}}
    <div class="mb-4 rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-center p-6 border-b border-[#D8C4B6]/40">
            <h3 class="text-xl font-semibold text-[#1b334e]">Daftar Caption</h3>
        </div>
        
        <div class="p-6">
            <!-- Filters -->
            <div class="mb-6 p-4 bg-[#f9b61a]/5 rounded-lg border border-[#D8C4B6]/40">
                <form method="GET" action="{{ route(($routePrefix ?? 'koordinator-humas.view').'.contents.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="jenis_konten" class="block text-sm font-medium text-gray-700 mb-2">Jenis Caption</label>
                        <select name="jenis_konten" id="jenis_konten" class="w-full border border-[#D8C4B6]/40 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#f9b61a]">
                            <option value="">Semua Jenis</option>
                            <option value="caption_berita" {{ request('jenis_konten') == 'caption_berita' ? 'selected' : '' }}>Caption Berita Redaksi</option>
                            <option value="caption_media_kreatif" {{ request('jenis_konten') == 'caption_media_kreatif' ? 'selected' : '' }}>Caption Media Kreatif</option>
                        </select>
                    </div>
                    <div>
                        <label for="media_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Media</label>
                        <select name="media_type" id="media_type" class="w-full border border-[#D8C4B6]/40 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#f9b61a]">
                            <option value="">Semua Tipe</option>
                            <option value="image" {{ request('media_type') == 'image' ? 'selected' : '' }}>Gambar</option>
                            <option value="video" {{ request('media_type') == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 bg-white text-[#1b334e] border border-[#D8C4B6]/40 px-4 py-2 rounded-lg text-sm hover:bg-[#f9b61a]/10 hover:shadow-md transition-all">
                            <i class="fas fa-search mr-2"></i>
                            Terapkan Filter
                        </button>
                        <a href="{{ route(($routePrefix ?? 'koordinator-humas.view').'.contents.index') }}" class="bg-white text-[#1b334e] border border-[#D8C4B6]/40 px-4 py-2 rounded-lg text-sm hover:bg-[#f9b61a]/10 transition-all">
                            <i class="fas fa-times mr-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-[#D8C4B6]/40 rounded-lg">
                    <thead class="bg-[#f9b61a]/5">
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
                    <tbody class="bg-white divide-y divide-[#D8C4B6]/40" id="contentsTableBody">
                        @forelse($contents as $content)
                            <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration + ($contents->currentPage() - 1) * $contents->perPage() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-[#1b334e]">{{ $content->judul }}</div>
                                    
                                    @if($content->isCaptionBerita() && $content->berita()->exists())
                                        <div class="text-sm text-[#1b334e]">
                                            <i class="fas fa-newspaper mr-1"></i> Berita: {{ $content->berita->title }}
                                        </div>
                                    @endif
                                    
                                    @if(($content->isCaptionMediaKreatif() || $content->isCaptionDesain()) && $content->desain()->exists())
                                        <div class="text-sm text-[#f9b61a]">
                                            <i class="fas fa-palette mr-1"></i> Desain: {{ $content->desain->judul }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-[#1b334e]/10 text-[#1b334e]">
                                        {{ App\Models\Content::getCaptionTypes()[$content->jenis_konten] ?? $content->jenis_konten }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($content->isCaptionBerita() && $content->berita()->exists())
                                        <div class="text-sm text-[#1b334e]">
                                            <i class="fas fa-newspaper mr-1"></i> {{ $content->berita->title }}
                                        </div>
                                    @elseif(($content->isCaptionMediaKreatif() || $content->isCaptionDesain()) && $content->desain()->exists())
                                        <div class="text-sm text-[#f9b61a]">
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
                                    <a href="{{ route(($routePrefix ?? 'koordinator-humas.view').'.contents.show', $content) }}" 
                                       class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
                                        <h5 class="text-lg font-medium text-[#1b334e] mb-2">Belum ada caption</h5>
                                        <p class="text-gray-500">Belum ada caption yang tersedia</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($contents->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $contents->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

