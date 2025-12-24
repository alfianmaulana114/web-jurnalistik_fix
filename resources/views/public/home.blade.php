{{-- Halaman Beranda Publik
    - Tujuan: Menampilkan headline, berita terbaru, media partner, dan sidebar
    - Prinsip: View tipis, komposisi via partials/components, logika di controller/service
    - Layout: menggunakan `layouts.app`
--}}
@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    {{-- Navigasi utama (publik) --}}
    <nav class="bg-white border-b" aria-label="Navigasi utama">
        <div class="container mx-auto px-4 sm:px-5 lg:px-6">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="/assets/images/header-home.png" alt="PARAGRAF MUDA" class="h-8 w-auto">
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

    {{-- Flash messages: sukses / error --}}
    <div class="container mx-auto px-4 sm:px-5 lg:px-6 pt-4">
        @include('partials.flash')
    </div>

    {{-- Konten utama beranda --}}
    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 sm:px-5 lg:px-6 py-6 sm:py-8">
            {{-- Bar Filter Kategori (inspirasi Figma) --}}
            
            {{-- Bagian Headline: 1 berita utama + ringkasan samping --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
                @if($latestNews)
                    {{-- Main Headline --}}
                    <div class="lg:col-span-8">
                        <div class="relative h-64 sm:h-80 md:h-[420px] lg:h-[500px] rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow group cursor-pointer">
                            <a href="{{ route('news.show', $latestNews->slug) }}">
                                <img src="{{ $latestNews->image_url }}" 
                                     alt="{{ $latestNews->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full text-white bg-red-600 mb-4">
                                        {{ $latestNews->category?->name ?? 'Berita' }}
                                    </span>
                                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 leading-tight">{{ $latestNews->title }}</h1>
                                    <p class="text-gray-200 text-sm sm:text-base mb-4 line-clamp-2">
                                        {{ Str::limit(strip_tags($latestNews->content ?? ''), 150) }}
                                    </p>
                                    <div class="flex items-center text-sm text-gray-300">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $latestNews->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Side Headlines --}}
                    <div class="lg:col-span-4 md:h-[420px] lg:h-[500px] grid grid-rows-4 gap-4">
                        @forelse($recentNews as $news)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 hover:-translate-y-0.5 h-full">
                                <a href="{{ route('news.show', $news->slug) }}" class="flex group h-full">
                                    <div class="w-2/5 relative overflow-hidden">
                                        <img src="{{ $news->image_url }}" 
                                             alt="{{ $news->title }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                    </div>
                                    <div class="w-3/5 p-3 flex flex-col justify-between h-full">
                                        <div>
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded text-white bg-[#F7C600] mb-2">
                                                {{ $news->category?->name ?? 'Berita' }}
                                            </span>
                                            <h3 class="font-bold text-gray-800 text-sm line-clamp-3 group-hover:text-[#F7C600] transition-colors">
                                                {{ $news->title }}
                                            </h3>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2">
                                            {{ $news->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Tidak ada berita terbaru</p>
                        @endforelse
                    </div>
                @else
                    <div class="col-span-12 text-center py-12">
                        <p class="text-gray-500">Belum ada berita tersedia</p>
                    </div>
                @endif
            </div>

            {{-- Area grid 12 kolom: konten + sidebar --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
                {{-- Kolom konten (8/12): media partner + daftar berita --}}
                <div class="lg:col-span-8">
                    {{-- Media Partner Section --}}
                    <div class="mb-12">
                        <div class="flex items-center justify-between border-b-2 border-gray-300 mb-6 pb-2">
                            <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">Media Partner</h2>
                            <a href="#" class="text-sm font-semibold text-[#F7C600] hover:text-[#E0B300] transition-colors">Lihat Semua →</a>
                        </div>
                        @if(($mediaPartnerNews ?? collect())->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                                @foreach($mediaPartnerNews as $mediaPartner)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                                        <div class="relative h-40 md:h-48 overflow-hidden">
                                            <img src="{{ $mediaPartner->image_url }}" 
                                                 alt="{{ $mediaPartner->title }}"
                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                        </div>
                                        <div class="p-4">
                                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full text-white bg-[#F7C600] mb-3">
                                                {{ $mediaPartner->type?->name ?? 'Media Partner' }}
                                            </span>
                                            <h3 class="font-bold text-base text-gray-900 mb-2 line-clamp-2 group-hover:text-[#F7C600] transition-colors">
                                                <a href="{{ route('news.show', $mediaPartner->slug) }}">
                                                    {{ $mediaPartner->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 text-sm line-clamp-2 mb-4">
                                                {{ Str::limit(strip_tags($mediaPartner->content ?? ''), 150) }}
                                            </p>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $mediaPartner->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Belum ada berita media partner</p>
                            </div>
                        @endif
                    </div>

                    {{-- Latest News Section --}}
                    <div>
                        <div class="flex items-center justify-between border-b-2 border-gray-300 mb-6 pb-2">
                            <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">Berita Terbaru</h2>
                            <a href="#" class="text-sm font-semibold text-[#F7C600] hover:text-[#E0B300] transition-colors">Lihat Semua →</a>
                        </div>
                        @if(($allNews ?? collect())->count() > 0)
                            <div class="space-y-4">
                                @foreach($allNews as $news)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                                        <div class="flex flex-col md:flex-row">
                                            <div class="md:w-2/5 relative overflow-hidden">
                                                <div class="h-20 md:h-28">
                                                    <a href="{{ route('news.show', $news->slug) }}">
                                                         <img src="{{ $news->image_url }}" 
                                                              alt="{{ $news->title }}"
                                                              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                              onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="md:w-3/5 p-3 md:p-4 flex flex-col justify-between">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full text-white bg-[#F7C600]">
                                                            {{ $news->category?->name ?? 'Berita' }}
                                                        </span>
                                                        <span class="text-sm text-gray-500 flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $news->created_at->format('d M Y, H:i') }}
                                                        </span>
                                                    </div>
                                                    <h3 class="text-base md:text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-[#F7C600] transition-colors">
                                                        <a href="{{ route('news.show', $news->slug) }}">
                                                            {{ $news->title }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-gray-600 text-sm line-clamp-2 md:line-clamp-3 mb-3">
                                                        {{ Str::limit(strip_tags($news->content ?? ''), 200) }}
                                                    </p>
                                                </div>
                                                <a href="{{ route('news.show', $news->slug) }}" class="text-[#F7C600] font-semibold text-sm hover:text-[#E0B300] inline-flex items-center">
                                                    Baca Selengkapnya
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Belum ada berita tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar (4/12): populer + kategori --}}
                <div class="lg:col-span-4">
                    {{-- Popular News --}}
                    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 border-b-2 border-[#F7C600] -mb-[2px] pb-3 mb-6 uppercase tracking-tight">Berita Populer</h2>
                        @if(($popularNews ?? collect())->count() > 0)
                            <div class="space-y-5">
                                @foreach(($popularNews ?? collect()) as $index => $news)
                                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                                        <span class="text-3xl font-bold text-[#F7C600] leading-none min-w-[2rem]">{{ $index + 1 }}</span>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2 hover:text-[#F7C600] transition-colors">
                                                <a href="{{ route('news.show', $news->slug) }}">
                                                    {{ $news->title }}
                                                </a>
                                            </h3>
                                            <span class="text-xs text-gray-500">
                                                {{ $news->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Tidak ada berita populer</p>
                        @endif
                    </div>

                    {{-- Categories --}}
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 border-b-2 border-[#F7C600] -mb-[2px] pb-3 mb-6 uppercase tracking-tight">Kategori</h2>
                        <div class="space-y-2">
                            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#FFF4CC] hover:text-[#F7C600] transition-colors text-gray-700 text-sm">Nasional</a>
                            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#FFF4CC] hover:text-[#F7C600] transition-colors text-gray-700 text-sm">Internasional</a>
                            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#FFF4CC] hover:text-[#F7C600] transition-colors text-gray-700 text-sm">Media Partner</a>
                            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#FFF4CC] hover:text-[#F7C600] transition-colors text-gray-700 text-sm">Teknologi</a>
                            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#FFF4CC] hover:text-[#F7C600] transition-colors text-gray-700 text-sm">Olahraga</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer publik --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="container mx-auto px-4 sm:px-5 lg:px-6 py-10 lg:py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 mb-8">
                {{-- Brand Section --}}
                <div class="lg:col-span-1">
                    <div class="mb-4">
                        <img src="/assets/images/header-home.png" alt="PARAGRAF MUDA" class="h-10 mb-4">
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed mb-4">
                        Portal berita terpercaya yang menyajikan informasi aktual dan terpercaya untuk pembaca Indonesia.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-[#F7C600] transition-colors" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-[#F7C600] transition-colors" aria-label="Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-[#F7C600] transition-colors" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-[#F7C600] transition-colors" aria-label="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Tautan Cepat</h3>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Nasional</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Internasional</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Media Partner</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">Kategori</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Politik</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Ekonomi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Teknologi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Olahraga</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Hiburan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
@endsection