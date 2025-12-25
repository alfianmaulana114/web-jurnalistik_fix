@extends($layout ?? 'layouts.koordinator-media-kreatif')

@section('title', 'Detail Brief Berita')
@section('header', 'Detail Brief Berita (Read-Only)')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Read-Only Banner --}}
    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">{{ $brief->judul }}</h1>
            <p class="mt-1 text-sm text-gray-600">Dibuat pada {{ $brief->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route(($routePrefix ?? 'koordinator-media-kreatif.view').'.briefs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto">
        <!-- Brief Details -->
        <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
            <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                <h3 class="text-lg font-medium text-[#1b334e]">Detail Brief</h3>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $brief->tanggal ? $brief->tanggal->format('d M Y') : '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Brief</label>
                    <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap bg-gray-50 p-4 rounded-lg">{{ $brief->isi_brief }}</div>
                </div>

                @if($brief->link_referensi)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Link Referensi</label>
                    <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                        {{ $brief->link_referensi }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Related Content -->
        @if($brief->contents && $brief->contents->count() > 0)
        <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40 mt-6">
            <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                <h3 class="text-lg font-medium text-[#1b334e]">Konten Terkait</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($brief->contents as $content)
                    <div class="border border-[#D8C4B6]/40 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-[#1b334e]">
                                    <a href="{{ route(($routePrefix ?? 'koordinator-media-kreatif.view').'.contents.show', $content) }}" class="hover:text-[#f9b61a]">
                                        {{ $content->judul }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-600 mt-1">{{ $content->getTypeLabel() ?? 'Caption' }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">{{ Str::limit(strip_tags($content->caption ?? $content->konten ?? ''), 100) }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $content->creator->name ?? 'Unknown' }}</span>
                            <span>{{ $content->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

