{{-- Dashboard Sekretaris
    - Ringkasan administrasi: notulensi, proker, absen
    - Navigasi cepat untuk operasional sekretariat
--}}
@extends('layouts.sekretaris')

@section('title', 'Dashboard')
@section('header', 'Dashboard Sekretaris')

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
                    Kelola administrasi UKM Jurnalistik dengan mudah dan efisien
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
                    <i class="fas fa-clipboard text-5xl text-[#f9b61a]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_users'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total News -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_news'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Prokers -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Proker</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_prokers'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Notulensi -->
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Notulensi</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $stats['total_notulensi'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Divisi Statistics -->
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div>
                <h3 class="text-base font-semibold text-[#1b334e]">Statistik Per Divisi</h3>
                <p class="mt-0.5 text-xs text-gray-600">Ringkasan aktivitas per divisi</p>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                @foreach($divisiStats as $key => $data)
                <div class="text-center rounded-lg border border-[#D8C4B6]/40 bg-white p-4 shadow-sm transition-all hover:shadow-md">
                    <div class="text-2xl font-bold text-[#1b334e]">{{ $data['total'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">{{ $data['nama'] }}</div>
                    <div class="text-xs text-gray-500 mt-2">
                        <div>Berita: {{ $data['news'] }}</div>
                        <div>Proker: {{ $data['prokers'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Recent News -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Berita Terbaru</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Berita terbaru yang dipublikasikan</p>
                </div>
            </div>
            <div class="p-5">
                @forelse($recentActivities['news'] as $news)
                <div class="mb-3 pb-3 border-b border-[#D8C4B6]/40 last:border-0 last:pb-0">
                    <h4 class="text-sm font-medium text-[#1b334e]">{{ $news->title }}</h4>
                    <p class="text-xs text-gray-600 mt-1">{{ $news->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4 text-sm">Tidak ada berita</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Prokers -->
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div>
                    <h3 class="text-base font-semibold text-[#1b334e]">Program Kerja Terbaru</h3>
                    <p class="mt-0.5 text-xs text-gray-600">Program kerja yang baru ditambahkan</p>
                </div>
            </div>
            <div class="p-5">
                @forelse($recentActivities['prokers'] as $proker)
                <div class="mb-3 pb-3 border-b border-[#D8C4B6]/40 last:border-0 last:pb-0">
                    <h4 class="text-sm font-medium text-[#1b334e]">{{ $proker->nama_proker }}</h4>
                    <p class="text-xs text-gray-600 mt-1">{{ $proker->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4 text-sm">Tidak ada proker</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

