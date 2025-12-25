@extends($layout ?? 'layouts.sekretaris')

@section('title', 'Desain Media')
@section('header', 'Desain Media (Read-Only)')

@section('content')
<div class="space-y-6">
    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-[#1b334e]">Desain Media</h2>
            <p class="mt-1 text-sm text-gray-600">Lihat desain media sederhana (Read-Only)</p>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all p-6">
        <form method="GET" action="{{ route(($routePrefix ?? 'sekretaris.view').'.designs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Judul desain..." class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
            </div>
            <div>
                <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                <select name="jenis" id="jenis" class="mt-1 block w-full border-[#D8C4B6]/40 rounded-lg shadow-sm focus:ring-[#f9b61a] focus:border-[#f9b61a] sm:text-sm">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\Design::getJenisOptions() as $key => $label)
                        <option value="{{ $key }}" {{ request('jenis') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 hover:shadow-md focus:outline-none transition-all duration-150">
                    <i class="fas fa-search mr-2"></i>
                    Terapkan Filter
                </button>
                <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.designs.index') }}" class="px-4 py-2 bg-white text-[#1b334e] border border-[#D8C4B6]/40 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-[#f9b61a]/10 focus:outline-none transition-all duration-150">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-[#D8C4B6]/40 shadow-sm hover:shadow-md transition-all rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
            <h3 class="text-lg font-medium text-[#1b334e]">Daftar Desain</h3>
        </div>
        @if($designs && $designs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#D8C4B6]/40">
                    <thead class="bg-[#f9b61a]/5">
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
                    <tbody class="bg-white divide-y divide-[#D8C4B6]/40" id="designsTableBody">
                        @foreach($designs as $design)
                            <tr class="hover:bg-[#f9b61a]/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.designs.show', $design) }}" class="font-medium hover:text-[#f9b61a]">
                                        {{ Str::limit($design->judul, 60) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php($jenisOptions = \App\Models\Design::getJenisOptions())
                                    @if($design->jenis === 'desain')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#1b334e]/10 text-[#1b334e]">
                                            {{ $jenisOptions[$design->jenis] ?? ucfirst($design->jenis) }}
                                        </span>
                                    @elseif($design->jenis === 'video')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#f9b61a]/10 text-[#f9b61a]">
                                            {{ $jenisOptions[$design->jenis] ?? ucfirst($design->jenis) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#D8C4B6] text-[#1b334e]">
                                            {{ $jenisOptions[$design->jenis] ?? ucfirst($design->jenis) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($design->berita)
                                        <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.news.show', $design->berita->id) }}" class="text-[#1b334e] hover:text-[#f9b61a]">
                                            {{ Str::limit($design->berita->title ?? $design->berita->judul ?? 'Berita', 40) }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Tidak terkait</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($design->media_url)
                                        <a href="{{ $design->media_url }}" target="_blank" rel="noopener" class="text-[#1b334e] hover:text-[#f9b61a]">
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
                                    <a href="{{ route(($routePrefix ?? 'sekretaris.view').'.designs.show', $design) }}" class="text-[#1b334e] hover:bg-[#f9b61a]/10 p-2 rounded-lg transition-all" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(method_exists($designs, 'links'))
                <div class="px-6 py-4 border-t border-[#D8C4B6]/40">
                    {{ $designs->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-palette text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-[#1b334e] mb-2">Belum ada desain</h3>
                <p class="text-gray-500">Belum ada desain yang tersedia</p>
            </div>
        @endif
    </div>
</div>
@endsection

