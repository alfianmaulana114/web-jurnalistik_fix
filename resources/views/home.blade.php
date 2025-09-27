@extends('layouts.app')

@push('styles')
    <style>
        .news-item {
            position: relative;
        }

        .news-item img {
            max-width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .news-item:hover img {
            transform: scale(1.05);
        }

        .news-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .news-item:hover .news-title {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-[90rem] mx-auto bg-white shadow-lg">
        <!-- Header dengan Logo -->
        <header class="bg-white p-4 border-b border-gray-200">
            <div class="text-center">
                <img src="/assets/images/header-home.png" alt="PARAGRAF MUDA" class="h-16 mx-auto">
            </div>
        </header>

        <!-- Navbar -->
        <nav class="bg-blue-900 text-white p-3">
            <div
                class="flex flex-wrap items-center space-x-2 text-base nav-container overflow-x-auto whitespace-nowrap px-2">
                <a href="#" class="px-3 py-1.5 hover:bg-blue-800 rounded-lg transition duration-200 nav-link">Tentang</a>
                <a href="#" class="px-3 py-1.5 hover:bg-blue-800 rounded-lg transition duration-200 nav-link">Halaman
                    Utama</a>
                <a href="#" class="px-3 py-1.5 hover:bg-blue-800 rounded-lg transition duration-200 nav-link">Media
                    Partner</a>
                <a href="#" class="px-3 py-1.5 hover:bg-blue-800 rounded-lg transition duration-200 nav-link">Berita
                    Nasional</a>
                <a href="#" class="px-3 py-1.5 hover:bg-blue-800 rounded-lg transition duration-200 nav-link">Berita
                    Internasional</a>
                <a href="#" class="px-3 py-1.5 hover:bg-blue-800 rounded-lg transition duration-200 nav-link">Berita
                    Internal</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex flex-col md:flex-row">
            <!-- Left Content (News) -->
            <div class="w-full md:w-3/4 p-4">


                <!-- Main News Image -->
                @if($latestNews)
                    <a href="/news/{{ $latestNews->slug }}" class="block mb-6">
                        <div class="news-item w-full aspect-video rounded-lg shadow-md overflow-hidden mt-[9px]">
                            <img src="{{ asset($latestNews->image) }}" alt="{{ $latestNews->title }}"
                                class="w-full h-full object-cover"
                                onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                            <div class="news-title">
                                <h3 class="text-xl font-bold">{{ $latestNews->title }}</h3>
                                <p class="text-sm text-gray-300">{{ $latestNews->category }}</p>
                            </div>
                        </div>

                    </a>
                @endif

                <!-- Other News Items -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    @foreach($recentNews as $news)
                        <a href="/news/{{ $news->slug }}" class="block">
                            <div
                                class="news-item aspect-video rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                <div class="news-title">
                                    <h3 class="font-bold">{{ $news->title }}</h3>
                                    <p class="text-sm text-gray-300">{{ $news->category }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Media Partner Section -->
                <div class="mt-6 w-full">
                    <div class="bg-yellow-500 flex justify-between items-center p-3 mb-4 rounded-t-lg">
                        <h3 class="text-lg font-bold text-white">Media Partner</h3>
                        <a href="#" class="text-sm font-semibold text-white hover:underline">Selengkapnya &gt;</a>
                    </div>
                    <div class="space-y-6">
                        @foreach($mediaPartnerNews as $mediaPartner)
                            <div class="flex flex-col sm:flex-row gap-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                <!-- Image Card -->
                                <div class="w-full sm:w-1/3">
                                    <div class="news-item aspect-video rounded-lg overflow-hidden">
                                        <img src="{{ asset($mediaPartner->image) }}" 
                                             alt="{{ $mediaPartner->title }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                        <div class="news-title">
                                            <h3 class="font-bold">{{ $mediaPartner->title }}</h3>
                                            <p class="text-sm text-gray-300">{{ $mediaPartner->subcategory }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- News Content -->
                                <div class="w-full sm:w-2/3 p-4">
                                    <div class="flex flex-col h-full">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-blue-600">
                                            <a href="/news/{{ $mediaPartner->slug }}">{{ $mediaPartner->title }}</a>
                                        </h3>
                                        <div class="flex items-center text-sm text-gray-500 mb-3">
                                            <i class="far fa-calendar-alt mr-2"></i>
                                            <span>{{ \Carbon\Carbon::parse($mediaPartner->created_at)->format('d M Y') }}</span>
                                            <span class="mx-2">•</span>
                                            <span class="text-yellow-600">{{ $mediaPartner->subcategory }}</span>
                                        </div>
                                        <p class="text-gray-600 line-clamp-3">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($mediaPartner->content), 255) }}
                                        </p>
                                        <div class="mt-auto pt-4">
                                            <a href="/news/{{ $mediaPartner->slug }}" 
                                               class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                                                Baca Selengkapnya 
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
                <!-- Seluruh Berita Section -->
<div class="mt-6 w-full">
    <div class="bg-blue-900 flex justify-between items-center p-3 mb-4 rounded-t-lg">
        <h3 class="text-lg font-bold text-white">Seluruh Berita</h3>
        <a href="#" class="text-sm font-semibold text-white hover:underline">Selengkapnya &gt;</a>
    </div>
    <div class="space-y-6">
        @foreach($allNews->take(5) as $news)
            <div class="flex flex-col sm:flex-row gap-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                <!-- Image Card -->
                <div class="w-full sm:w-1/3">
                    <div class="news-item aspect-video rounded-lg overflow-hidden">
                        <img src="{{ asset($news->image) }}" 
                             alt="{{ $news->title }}" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                        <div class="news-title">
                            <h3 class="font-bold">{{ $news->title }}</h3>
                            <p class="text-sm text-gray-300">{{ $news->category }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- News Content -->
                <div class="w-full sm:w-2/3 p-4">
                    <div class="flex flex-col h-full">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-blue-600">
                            <a href="/news/{{ $news->slug }}">{{ $news->title }}</a>
                        </h3>
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($news->created_at)->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <span class="text-blue-600">{{ $news->category }}</span>
                        </div>
                        <p class="text-gray-600 line-clamp-3">
                            {{ \Illuminate\Support\Str::limit(strip_tags($news->content), 255) }}
                        </p>
                        <div class="mt-auto pt-4">
                            <a href="/news/{{ $news->slug }}" 
                               class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800">
                                Baca Selengkapnya 
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
            </div>

            <!-- Right Sidebar (Recent News) -->
            <div class="w-full md:w-1/4 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-bold mb-4 border-b-2 border-yellow-500 pb-2 inline-block">Berita Terbaru</h3>
                <div class="space-y-4">
                    @foreach($sidebarNews as $recentNews)
                        <a href="/news/{{ $recentNews->slug }}" class="block">
                            <div
                                class="news-item w-full aspect-video rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                <img src="{{ asset($recentNews->image) }}" alt="{{ $recentNews->title }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                                <div class="news-title">
                                    <h4 class="font-bold">{{ $recentNews->title }}</h4>
                                    <p class="text-xs text-gray-300">{{ $recentNews->category }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-8">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Web Jurnalistik</h3>
                        <p class="text-gray-400">Platform berita dan jurnalistik terpercaya</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-4">Tautan</h3>
                        <ul class="space-y-2">
                            <li><a href="/" class="text-gray-400 hover:text-white">Beranda</a></li>
                            <li><a href="/news" class="text-gray-400 hover:text-white">Berita</a></li>
                            <li><a href="/about" class="text-gray-400 hover:text-white">Tentang</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-4">Kontak</h3>
                        <ul class="space-y-2 text-gray-400">
                            <li>Email: info@webjurnalistik.com</li>
                            <li>Telepon: (021) 1234567</li>
                            <li>Alamat: Jl. Contoh No. 123</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Web Jurnalistik. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </div>
@endsection

