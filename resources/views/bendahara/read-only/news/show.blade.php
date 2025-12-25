@extends($layout ?? 'layouts.bendahara')

@section('title', 'Detail Berita')
@section('header', 'Detail Berita (Read-Only)')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
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

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-[#D8C4B6]/40">
        <!-- Header -->
        <div class="p-6 border-b border-[#D8C4B6]/40">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-[#1b334e] mb-2">{{ $news->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span><i class="fas fa-user mr-1"></i>{{ $news->user->name ?? 'Tidak diketahui' }}</span>
                        <span><i class="fas fa-calendar mr-1"></i>{{ $news->created_at->format('d M Y, H:i') }}</span>
                        <span><i class="fas fa-eye mr-1"></i>{{ $news->views }} views</span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route(($routePrefix ?? 'bendahara.view').'.news.index') }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Image -->
        @if($news->image)
        <div class="relative">
            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-96 object-cover">
        </div>
        @endif

        <!-- Content -->
        <div class="p-6">
            <!-- Meta Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Kategori</h4>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        {{ $news->category->name ?? 'Tidak ada kategori' }}
                    </span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Tipe</h4>
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        {{ $news->type->name ?? 'Tidak ada tipe' }}
                    </span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Genre</h4>
                    <div class="flex flex-wrap gap-1">
                        @if($news->genres && $news->genres->count() > 0)
                            @foreach($news->genres as $genre)
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-500 text-sm">Tidak ada genre</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                <h4 class="font-semibold text-gray-700 mb-3">Informasi SEO</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h5 class="text-sm font-medium text-gray-600 mb-1">Meta Description</h5>
                        <p class="text-sm text-gray-700">{{ $news->meta_description ?? 'Tidak ada meta description' }}</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-medium text-gray-600 mb-1">Tags</h5>
                        <p class="text-sm text-gray-700">{{ $news->tags ?? 'Tidak ada tags' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h5 class="text-sm font-medium text-gray-600 mb-1">Keyword</h5>
                        <p class="text-sm text-gray-700">{{ $news->keyword ?? 'Tidak ada keyword' }}</p>
                    </div>
                </div>
            </div>

            <!-- Article Content -->
            <div class="prose max-w-none">
                <h3 class="text-xl font-semibold text-[#1b334e] mb-4">Konten Berita</h3>
                <div class="text-gray-700 leading-relaxed">
                    {!! $news->content !!}
                </div>
            </div>

            <!-- Statistics -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-700 mb-3">Statistik</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $news->views }}</div>
                        <div class="text-sm text-gray-500">Views</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $news->created_at->diffForHumans() }}</div>
                        <div class="text-sm text-gray-500">Dipublikasi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $news->updated_at->diffForHumans() }}</div>
                        <div class="text-sm text-gray-500">Diupdate</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-[#D8C4B6]/40">
            <div class="flex justify-between items-center">
                <a href="{{ route('news.show', $news->slug) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>Lihat di Website
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .prose {
        max-width: none;
    }
    .prose img {
        border-radius: 0.5rem;
        margin: 1rem 0;
    }
    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
        color: #374151;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    .prose ul, .prose ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }
    .prose blockquote {
        border-left: 4px solid #e5e7eb;
        padding-left: 1rem;
        margin: 1rem 0;
        font-style: italic;
        color: #6b7280;
    }
</style>
@endpush
@endsection

