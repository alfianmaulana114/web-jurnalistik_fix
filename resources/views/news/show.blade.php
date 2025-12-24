<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $news->title }} - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
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
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="bg-gray-100 min-h-screen">
    <!-- Top Navigation -->
    <nav class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="/assets/images/header-home.png" alt="PARAGRAF MUDA" class="h-8">
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Nasional</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Internasional</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Media Partner</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Tentang</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <!-- Breadcrumb -->
                <nav class="mb-4">
                    <ol class="flex items-center space-x-2 text-sm text-gray-600">
                        <li><a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a></li>
                        <li class="text-gray-400">/</li>
                        <li><span class="text-gray-900">{{ $news->category->name ?? 'Berita' }}</span></li>
                    </ol>
                </nav>

                <!-- Article -->
                <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="tag-label bg-red-600">
                                {{ $news->category->name ?? 'Berita' }}
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ $news->created_at->format('d M Y, H:i') }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-eye mr-1"></i>{{ $news->views }} views
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $news->title }}</h1>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            <span>{{ $news->user->name ?? 'Admin' }}</span>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($news->image)
                    <div class="relative">
                        <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="w-full h-96 object-cover">
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Article Content -->
                        <div class="prose max-w-none mb-8">
                            <div class="text-gray-700 leading-relaxed">
                                {!! $news->content !!}
                            </div>
                        </div>

                        <!-- Tags & Genre -->
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            @if($news->tags)
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <span class="text-sm font-medium text-gray-700">Tags:</span>
                                @foreach(explode(',', $news->tags) as $tag)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                            </div>
                            @endif
                            @if($news->genres && $news->genres->count() > 0)
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-700">Genre:</span>
                                @foreach($news->genres as $genre)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </article>

                
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <!-- Related News -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Berita Terkait</h2>
                    <div class="space-y-4">
                        @php
                            $relatedNews = \App\Models\News::where('news_category_id', $news->news_category_id)
                                ->where('id', '!=', $news->id)
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        @forelse($relatedNews as $related)
                        <div class="flex gap-3">
                            @if($related->image)
                            <div class="w-20 h-20 flex-shrink-0">
                                <img src="{{ asset($related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover rounded">
                            </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold text-sm hover:text-blue-600">
                                    <a href="{{ route('news.show', $related->slug) }}">
                                        {{ Str::limit($related->title, 60) }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $related->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Tidak ada berita terkait</p>
                        @endforelse
                    </div>
                </div>

                <!-- Popular News -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Berita Populer</h2>
                    <div class="space-y-4">
                        @php
                            $popularNews = \App\Models\News::orderBy('views', 'desc')
                                ->where('id', '!=', $news->id)
                                ->take(5)
                                ->get();
                        @endphp
                        @forelse($popularNews as $index => $popular)
                        <div class="flex items-start gap-3">
                            <span class="text-2xl font-bold text-blue-600">{{ $index + 1 }}</span>
                            <div class="flex-1">
                                <h3 class="font-medium text-sm hover:text-blue-600">
                                    <a href="{{ route('news.show', $popular->slug) }}">
                                        {{ Str::limit($popular->title, 60) }}
                                    </a>
                                </h3>
                                <span class="text-xs text-gray-500">
                                    {{ $popular->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Tidak ada berita populer</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

