@extends($layout ?? 'layouts.koordinator-media-kreatif')

@section('title', 'Detail Funfact')
@section('header', 'Detail Funfact (Read-Only)')

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
            <h1 class="text-2xl font-bold text-[#1b334e]">{{ $funfact->judul }}</h1>
            <p class="mt-1 text-sm text-gray-600">Dibuat pada {{ $funfact->created_at->format('d M Y') }} oleh {{ $funfact->creator->name ?? '-' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route(($routePrefix ?? 'koordinator-media-kreatif.view').'.funfacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto">
        <!-- Funfact Details -->
        <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
            <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-lightbulb text-purple-600"></i>
                        </div>
                    </div>
                    <h3 class="ml-4 text-lg font-medium text-[#1b334e]">Detail Funfact</h3>
                </div>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul</label>
                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $funfact->judul }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Funfact</label>
                    <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap bg-gray-50 p-4 rounded-md">{{ $funfact->isi }}</div>
                </div>

                @if($funfact->link_referensi)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Link Referensi</label>
                    <div class="mt-1 space-y-2">
                        @php($links = $funfact->getLinksArray())
                        @if(count($links) > 0)
                            @foreach($links as $link)
                                <div class="flex items-center space-x-2 bg-gray-50 p-3 rounded-md">
                                    <i class="fas fa-external-link-alt text-gray-400"></i>
                                    <a href="{{ $link }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm break-all">
                                        {{ $link }}
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="text-sm text-gray-500 whitespace-pre-wrap bg-gray-50 p-4 rounded-md">{{ $funfact->link_referensi }}</div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Dibuat Oleh</label>
                            <p class="mt-1 text-gray-900">{{ $funfact->creator->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Diperbarui</label>
                            <p class="mt-1 text-gray-900">{{ $funfact->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

