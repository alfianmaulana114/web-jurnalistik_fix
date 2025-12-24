@extends('layouts.koordinator-media-kreatif')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Media Kreatif')

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
                    Kelola aktivitas Divisi Media Kreatif dengan mudah dan efisien
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
                    <i class="fas fa-palette text-5xl text-[#f9b61a]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Anggota Media Kreatif</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $users->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Desain</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $design_total }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-palette text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Caption</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $captions_total }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-closed-captioning text-xl"></i>
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Kas Lunas</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $kas_lunas }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Desain Terbaru -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Desain Terbaru</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Desain yang baru dibuat</p>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($designs as $design)
                    <div class="flex items-start space-x-3">
                        @if($design->gambar)
                        <img src="{{ asset($design->gambar) }}" alt="{{ $design->judul }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-[#1b334e] line-clamp-2">{{ $design->judul }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $design->created_at->diffForHumans() }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $design->status === 'published' ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-700' }}">
                                    {{ ucfirst($design->status ?? 'draft') }}
                                </span>
                                @if($design->berita)
                                <i class="fas fa-newspaper ml-3 mr-1"></i>
                                <span class="truncate max-w-xs">{{ $design->berita->title }}</span>
                                @endif
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

        <!-- Ringkasan Kas Anggota -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Ringkasan Kas Anggota</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Status pembayaran kas divisi media kreatif</p>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 shadow-sm">
                        <p class="text-xs text-gray-600">Total Catatan Kas</p>
                        <p class="text-2xl font-bold text-[#1b334e] mt-1">{{ $kas_total_records }}</p>
                    </div>
                    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 shadow-sm">
                        <p class="text-xs text-gray-600">Belum Lunas</p>
                        <p class="text-2xl font-bold text-[#1b334e] mt-1">{{ $kas_belum_lunas }}</p>
                    </div>
                    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 shadow-sm col-span-2">
                        <p class="text-xs text-gray-600">Total Terkumpul</p>
                        <p class="text-2xl font-bold text-[#1b334e] mt-1">Rp {{ number_format($kas_total_terkumpul, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection