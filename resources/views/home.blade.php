@extends('layouts.app')

@push('styles')
    <style>
        .news-item {
            position: relative;
            overflow: hidden;
        }

        .news-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
        }

        .section-header {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            color: #1a365d;
            border-bottom: 2px solid #1a365d;
            margin-bottom: -2px;
            padding-bottom: 0.5rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .tag-label {
            display: inline-block;
            padding: 2px 8px;
            font-size: 0.75rem;
            border-radius: 3px;
            color: white;
        }

        .headline-overlay {
            background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.8) 100%);
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 4rem 1.5rem 1.5rem;
        }
    </style>
@endpush

@section('content')
    <!-- Top Navigation -->
    <nav class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="/assets/images/header-home.png" alt="PARAGRAF MUDA" class="h-8">
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Nasional</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Internasional</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Media Partner</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Tentang</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Headline Section -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
                @if($latestNews)
                    <!-- Main Headline -->
                    <div class="lg:col-span-8">
                        <div class="relative h-[450px] rounded-lg overflow-hidden shadow-lg">
                            <a href="{{ route('news.show', $latestNews->slug) }}">
                                <img src="{{ asset($latestNews->image ?? 'images/no-image.jpg') }}" 
                                     alt="{{ $latestNews->title }}"
                                     class="w-full h-full object-cover">
                                <div class="headline-overlay">
                                    <span class="tag-label bg-red-600 mb-3">
                                        {{ $latestNews->category?->name ?? 'Berita' }}
                                    </span>
                                    <h1 class="text-3xl font-bold text-white mb-2">{{ $latestNews->title }}</h1>
                                    <p class="text-gray-200 text-sm">
                                        {{ Str::limit(strip_tags($latestNews->content), 120) }}
                                    </p>
                                    <div class="mt-3 text-sm text-gray-300">
                                        <i class="far fa-clock mr-2"></i>
                                        {{ $latestNews->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Side Headlines -->
                    <div class="lg:col-span-4 space-y-4">
                        @foreach($recentNews->take(3) as $news)
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <a href="{{ route('news.show', $news->slug) }}" class="flex">
                                    <div class="w-1/3">
                                        <img src="{{ asset($news->image ?? 'images/no-image.jpg') }}" 
                                             alt="{{ $news->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="w-2/3 p-4">
                                        <span class="tag-label bg-blue-600 text-xs">
                                            {{ $news->category?->name ?? 'Berita' }}
                                        </span>
                                        <h3 class="font-semibold text-gray-800 mt-2 line-clamp-2">
                                            {{ $news->title }}
                                        </h3>
                                        <div class="text-xs text-gray-500 mt-2">
                                            {{ $news->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Main Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- News Content -->
                <div class="lg:col-span-8">
                    <!-- Media Partner Section -->
                    <div class="mb-8">
                        <div class="section-header">
                            <h2 class="section-title">Media Partner</h2>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($mediaPartnerNews as $mediaPartner)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="aspect-video">
                                        <img src="{{ asset($mediaPartner->image ?? 'images/no-image.jpg') }}" 
                                             alt="{{ $mediaPartner->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <span class="tag-label bg-yellow-500 text-xs">
                                            {{ $mediaPartner->type?->name ?? 'Media Partner' }}
                                        </span>
                                        <h3 class="font-bold text-lg mt-2 mb-2 hover:text-blue-600">
                                            <a href="{{ route('news.show', $mediaPartner->slug) }}">
                                                {{ $mediaPartner->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                            {{ Str::limit(strip_tags($mediaPartner->content), 150) }}
                                        </p>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="far fa-clock mr-2"></i>
                                            {{ $mediaPartner->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Latest News Section -->
                    <div>
                        <div class="section-header">
                            <h2 class="section-title">Berita Terbaru</h2>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                        </div>
                        <div class="space-y-6">
                            @foreach($allNews as $news)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="flex flex-col md:flex-row">
                                        <div class="md:w-1/3">
                                            <div class="h-48 md:h-full">
                                                <img src="{{ asset($news->image ?? 'images/no-image.jpg') }}" 
                                                     alt="{{ $news->title }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                        <div class="md:w-2/3 p-4">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="tag-label bg-blue-600">
                                                    {{ $news->category?->name ?? 'Berita' }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    {{ $news->created_at->format('d M Y H:i') }}
                                                </span>
                                            </div>
                                            <h3 class="text-xl font-bold mb-2">
                                                <a href="{{ route('news.show', $news->slug) }}" 
                                                   class="hover:text-blue-600">
                                                    {{ $news->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 line-clamp-3 mb-4">
                                                {{ Str::limit(strip_tags($news->content), 200) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $allNews->links() }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <!-- Popular News -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="section-title mb-4">Berita Populer</h2>
                        <div class="space-y-4">
                            @foreach($recentNews->take(5) as $index => $news)
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl font-bold text-blue-600">{{ $index + 1 }}</span>
                                    <div>
                                        <h3 class="font-medium hover:text-blue-600">
                                            <a href="{{ route('news.show', $news->slug) }}">
                                                {{ $news->title }}
                                            </a>
                                        </h3>
                                        <span class="text-sm text-gray-500">
                                            {{ $news->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="section-title mb-4">Kategori</h2>
                        <!-- Tambahkan daftar kategori di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection