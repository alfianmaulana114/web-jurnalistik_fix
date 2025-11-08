@extends('layouts.koordinator-redaksi')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Redaksi')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-purple-100 mt-1">Kelola aktivitas Divisi Redaksi dengan mudah</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-pen-fancy text-6xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Divisi Redaksi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Anggota Redaksi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $redaksi_total }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $redaksi_coordinators }} Koordinator, {{ $redaksi_members }} Anggota</p>
                </div>
            </div>
        </div>

        <!-- Brief dari Litbang -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $brief_total }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $brief_urgent }} Mendesak, {{ $brief_pending }} Pending</p>
                </div>
            </div>
        </div>

        <!-- Caption -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-closed-captioning text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Caption</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $caption_total }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $caption_berita }} Berita, {{ $caption_desain }} Desain</p>
                </div>
            </div>
        </div>

        <!-- Design -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                    <i class="fas fa-palette text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Design</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $design_total }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $design_published }} Published, {{ $design_draft }} Draft</p>
                </div>
            </div>
        </div>
    </div>

    <!-- News Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $news_total }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-eye text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($news_total_views) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Berita Published</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $news_published }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Brief dari Litbang -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>Brief dari Litbang
                    </h3>
                    <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                        {{ $brief_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recent_briefs as $brief)
                    <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg border {{ $brief->status === 'urgent' ? 'border-red-200 bg-red-50' : '' }}">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $brief->judul }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($brief->deskripsi ?? '', 60) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                {{ $brief->creator->name ?? 'Tidak ada' }}
                                <i class="fas fa-clock ml-3 mr-1"></i>
                                {{ $brief->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full ml-3
                            @if($brief->status === 'urgent') bg-red-100 text-red-800
                            @elseif($brief->status === 'completed') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($brief->status ?? 'pending') }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada brief dari litbang</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Caption Terbaru -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-closed-captioning mr-2 text-green-600"></i>Caption Terbaru
                    </h3>
                    <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                        {{ $caption_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recent_captions as $caption)
                    <div class="p-3 bg-gray-50 rounded-lg border">
                        <p class="font-medium text-gray-900">{{ $caption->judul ?? 'Tanpa Judul' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($caption->konten ?? '', 60) }}</p>
                        <div class="flex items-center mt-2 text-xs text-gray-500">
                            <span class="px-2 py-1 bg-gray-200 rounded">
                                {{ ucfirst(str_replace('_', ' ', $caption->jenis_konten ?? 'caption')) }}
                            </span>
                            <i class="fas fa-clock ml-3 mr-1"></i>
                            {{ $caption->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-closed-captioning text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada caption</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Design dan Berita Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Design Terbaru -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-palette mr-2 text-pink-600"></i>Design Terbaru
                    </h3>
                    <span class="px-3 py-1 text-xs bg-pink-100 text-pink-800 rounded-full">
                        {{ $design_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recent_designs as $design)
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border">
                        @if($design->gambar)
                        <img src="{{ asset($design->gambar) }}" alt="{{ $design->judul }}" class="w-16 h-16 object-cover rounded">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $design->judul ?? 'Tanpa Judul' }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($design->deskripsi ?? '', 50) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <span class="px-2 py-1 rounded {{ $design->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($design->status ?? 'draft') }}
                                </span>
                                <i class="fas fa-clock ml-3 mr-1"></i>
                                {{ $design->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-palette text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada design</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Berita Terbaru -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-newspaper mr-2 text-red-600"></i>Berita Terbaru
                    </h3>
                    <a href="{{ route('koordinator-redaksi.news.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recent_news as $news)
                    <div class="flex items-start space-x-3">
                        @if($news->image)
                        <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="w-16 h-16 object-cover rounded">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 line-clamp-2">{{ $news->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $news->created_at->diffForHumans() }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $news->views ?? 0 }} views
                                <i class="fas fa-user ml-3 mr-1"></i>
                                {{ $news->user->name ?? 'Tidak ada' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-newspaper text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada berita</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Tren Bulanan</h3>
        </div>
        <div class="p-6">
            <canvas id="monthlyChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Monthly Chart untuk visualisasi tren
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthly_labels) !!},
            datasets: [{
                label: 'Berita',
                data: {!! json_encode($monthly_news_data) !!},
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4
            }, {
                label: 'Brief',
                data: {!! json_encode($monthly_brief_data) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }, {
                label: 'Caption',
                data: {!! json_encode($monthly_caption_data) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4
            }, {
                label: 'Design',
                data: {!! json_encode($monthly_design_data) !!},
                borderColor: 'rgb(236, 72, 153)',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endpush

