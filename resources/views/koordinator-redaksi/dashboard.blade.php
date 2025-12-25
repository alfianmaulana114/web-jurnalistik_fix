{{-- Dashboard Koordinator Redaksi
    - Ringkasan berita, penjadwalan, dan funfact
    - Fokus pada review dan publikasi konten
--}}
@extends('layouts.koordinator-redaksi')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Redaksi')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-6 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-[#1b334e] sm:text-3xl">
                    Selamat Datang, <span class="text-[#f9b61a]">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Kelola aktivitas Divisi Redaksi dengan mudah dan efisien
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                    <svg class="h-4 w-4 text-[#f9b61a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-[#f9b61a]/10">
                    <i class="fas fa-pen-fancy text-5xl text-[#f9b61a]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Divisi Redaksi -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Anggota Redaksi</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $redaksi_total }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $redaksi_coordinators }} Koordinator, {{ $redaksi_members }} Anggota</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Brief dari Litbang -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $brief_total }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Caption -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Caption</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $caption_total }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-closed-captioning text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Design -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Design</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $design_total }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-palette text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- News Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $news_total }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ number_format($news_total_views) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Bulan Ini</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-eye text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Berita Sudah Terbit</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-green-600">{{ $news_sudah_terbit }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $news_belum_terbit }} Belum Terbit (Bulan Ini)</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 transition-colors group-hover:bg-green-600 group-hover:text-white">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Brief dari Litbang -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Brief dari Litbang</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Brief terbaru dari divisi litbang</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-[#f9b61a]/10 px-2.5 py-0.5 text-xs font-medium text-[#1b334e]">
                        {{ $brief_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-2">
                    @forelse($recent_briefs as $brief)
                    <div class="flex items-start justify-between rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm transition-all hover:shadow-md">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-[#1b334e]">{{ $brief->judul }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($brief->isi_brief ?? '', 60) }}</p>
                            <div class="flex items-center mt-1.5 text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $brief->tanggal ? $brief->tanggal->format('d M Y') : 'Tanpa tanggal' }}
                                <i class="fas fa-clock ml-3 mr-1"></i>
                                {{ $brief->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada brief dari litbang</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Caption Terbaru -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Caption Terbaru</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Caption yang baru dibuat</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-[#f9b61a]/10 px-2.5 py-0.5 text-xs font-medium text-[#1b334e]">
                        {{ $caption_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-2">
                    @forelse($recent_captions as $caption)
                    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm transition-all hover:shadow-md">
                        <p class="text-sm font-medium text-[#1b334e]">{{ $caption->judul ?? 'Tanpa Judul' }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit(strip_tags($caption->caption ?? ''), 60) }}</p>
                        <div class="flex items-center mt-2 text-xs text-gray-500">
                            <span class="inline-flex items-center rounded-full bg-[#1b334e] px-2 py-0.5 text-xs font-medium text-white">
                                {{ ucfirst(str_replace('_', ' ', $caption->jenis_konten ?? 'caption')) }}
                            </span>
                            <i class="fas fa-clock ml-3 mr-1"></i>
                            {{ $caption->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-closed-captioning text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada caption</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Design dan Berita Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Design Terbaru -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Design Terbaru</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Design yang baru dibuat</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-[#f9b61a]/10 px-2.5 py-0.5 text-xs font-medium text-[#1b334e]">
                        {{ $design_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-2">
                    @forelse($recent_designs as $design)
                    <div class="flex items-start space-x-3 rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm transition-all hover:shadow-md">
                        @if($design->media_url)
                        <img src="{{ asset('storage/' . $design->media_url) }}" alt="{{ $design->judul }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-medium text-[#1b334e]">{{ $design->judul ?? 'Tanpa Judul' }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($design->catatan ?? '', 50) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-2 py-0.5 text-xs font-medium">
                                    {{ ucfirst($design->jenis ?? 'desain') }}
                                </span>
                                <i class="fas fa-clock ml-3 mr-1"></i>
                                {{ $design->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-palette text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada design</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Berita Terbaru -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Berita Terbaru</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Berita yang baru dipublikasikan</p>
                    </div>
                    <a href="{{ route('koordinator-redaksi.news.index') }}" class="text-xs font-medium text-[#1b334e] hover:text-[#f9b61a]">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($recent_news as $news)
                    @php
                        $sudahTerbit = $news->approval !== null && $news->caption !== null;
                    @endphp
                    <div class="flex items-start space-x-3">
                        @if($news->image)
                        <img src="{{ asset($news->image) }}" alt="{{ $news->title }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-[#1b334e] line-clamp-2">{{ $news->title }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $news->created_at->diffForHumans() }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-eye mr-1"></i>
                                {{ number_format($news->views ?? 0) }} views
                                @if($sudahTerbit)
                                <span class="inline-flex items-center rounded-full bg-green-50 text-green-700 px-2 py-0.5 text-xs font-medium ml-2">
                                    Sudah Terbit
                                </span>
                                @else
                                <span class="inline-flex items-center rounded-full bg-gray-50 text-gray-700 px-2 py-0.5 text-xs font-medium ml-2">
                                    Belum Terbit
                                </span>
                                @endif
                                <i class="fas fa-user ml-3 mr-1"></i>
                                {{ $news->user->name ?? 'Tidak ada' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-newspaper text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada berita</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div>
                <h3 class="text-base font-semibold text-[#1b334e]">Tren Bulanan</h3>
                <p class="mt-0.5 text-xs text-gray-600">Grafik aktivitas bulanan divisi redaksi</p>
            </div>
        </div>
        <div class="p-5">
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
                label: 'View',
                data: {!! json_encode($monthly_views_data) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah View'
                    }
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

