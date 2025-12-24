@extends('layouts.app')

@section('content')
    <nav class="bg-white border-b" aria-label="Navigasi utama">
        <div class="container mx-auto px-4 sm:px-5 lg:px-6">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="/assets/images/header-home.png" alt="PARAGRAF MUDA" class="h-8 w-auto">
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-[#F7C600] transition-colors focus:outline-none focus:ring-2 focus:ring-[#F7C600] focus:ring-offset-2 rounded">Beranda</a>
                    <a href="{{ route('public.category', 'nasional') }}" class="text-sm font-medium text-gray-700 hover:text-[#F7C600] transition-colors focus:outline-none focus:ring-2 focus:ring-[#F7C600] focus:ring-offset-2 rounded">Nasional</a>
                    <a href="{{ route('public.category', 'internasional') }}" class="text-sm font-medium text-gray-700 hover:text-[#F7C600] transition-colors focus:outline-none focus:ring-2 focus:ring-[#F7C600] focus:ring-offset-2 rounded">Internasional</a>
                    <a href="{{ route('public.type', 'media-partner') }}" class="text-sm font-medium text-gray-700 hover:text-[#F7C600] transition-colors focus:outline-none focus:ring-2 focus:ring-[#F7C600] focus:ring-offset-2 rounded">Media Partner</a>
                    <a href="{{ route('public.about') }}" class="text-sm font-medium text-gray-700 hover:text-[#F7C600] transition-colors focus:outline-none focus:ring-2 focus:ring-[#F7C600] focus:ring-offset-2 rounded">Tentang</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 sm:px-5 lg:px-6 py-6 sm:py-8">
            <div class="flex items-center justify-between border-b-2 border-gray-300 mb-6 pb-2">
                <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">{{ $title }}</h1>
                <a href="{{ route('home') }}" class="text-sm font-semibold text-[#F7C600] hover:text-[#E0B300] transition-colors">Kembali ke Beranda →</a>
            </div>

            @if($newsList->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($newsList as $news)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                            <div class="relative h-40 md:h-48 overflow-hidden">
                                <a href="{{ route('news.show', $news->slug) }}">
                                    <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                </a>
                            </div>
                            <div class="p-4">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full text-white bg-[#F7C600] mb-3">
                                    {{ $news->category?->name ?? $news->type?->name ?? 'Berita' }}
                                </span>
                                <h3 class="font-bold text-base text-gray-900 mb-2 line-clamp-2 group-hover:text-[#F7C600] transition-colors">
                                    <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ Str::limit(strip_tags($news->content ?? ''), 150) }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    {{ $news->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 flex justify-center">{{ $newsList->links() }}</div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Belum ada berita tersedia</p>
                </div>
            @endif
        </div>
    </div>
@endsection