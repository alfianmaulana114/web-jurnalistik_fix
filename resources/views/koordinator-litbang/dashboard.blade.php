@extends('layouts.koordinator-litbang')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Litbang')

@section('content')
<div class="space-y-6">
    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-6 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-[#1b334e] sm:text-3xl">
                    Selamat Datang, <span class="text-[#f9b61a]">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Kelola aktivitas Divisi Litbang dengan mudah dan efisien
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
                    <i class="fas fa-flask text-5xl text-[#f9b61a]"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Anggota Litbang --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Anggota Litbang</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $litbang_total }}</p>
                    <p class="mt-1.5 text-xs text-gray-500">{{ $litbang_coordinators }} Koordinator, {{ $litbang_members }} Anggota</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Brief --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $brief_total }}</p>
                    <p class="mt-1.5 text-xs text-gray-500">{{ $brief_with_ref }} dengan referensi</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Brief Bulan Ini --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Brief Bulan Ini</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $brief_this_month }}</p>
                    <p class="mt-1.5 text-xs text-gray-500">{{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Kas Lunas --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Kas Lunas</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $kas_lunas }}</p>
                    <p class="mt-1.5 text-xs text-gray-500">Dari {{ $kas_total_records }} catatan</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Brief Terbaru --}}
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-[#D8C4B6]/40 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Brief Terbaru</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Brief yang baru dibuat</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-[#1b334e] text-white">
                        {{ $brief_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($recent_briefs as $brief)
                    <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-[#1b334e]">{{ $brief->judul ?? 'Tanpa Judul' }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($brief->isi_brief ?? '', 60) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-link mr-1"></i>
                                <span class="truncate max-w-xs">{{ $brief->link_referensi ? Str::limit($brief->link_referensi, 40) : 'Tidak ada referensi' }}</span>
                                <i class="fas fa-clock ml-3 mr-1"></i>
                                {{ optional($brief->tanggal)->format('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-6xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada brief</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Statistik Brief & Kas --}}
        <div class="space-y-4">
            {{-- Statistik Brief --}}
            <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
                <div class="border-b border-[#D8C4B6]/40 p-5">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Statistik Brief</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Ringkasan brief divisi litbang</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 text-center shadow-sm">
                            <p class="text-2xl font-bold text-[#1b334e]">{{ $brief_with_ref }}</p>
                            <p class="text-xs text-gray-600 mt-1">Dengan Referensi</p>
                        </div>
                        <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 text-center shadow-sm">
                            <p class="text-2xl font-bold text-[#1b334e]">{{ $brief_without_ref }}</p>
                            <p class="text-xs text-gray-600 mt-1">Tanpa Referensi</p>
                        </div>
                        <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 text-center shadow-sm">
                            <p class="text-2xl font-bold text-[#1b334e]">{{ $brief_this_month }}</p>
                            <p class="text-xs text-gray-600 mt-1">Bulan Ini</p>
                        </div>
                        <div class="rounded-lg border border-[#D8C4B6]/40 bg-white p-4 text-center shadow-sm">
                            <p class="text-2xl font-bold text-[#1b334e]">{{ $brief_total }}</p>
                            <p class="text-xs text-gray-600 mt-1">Total Brief</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Kas --}}
            <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
                <div class="border-b border-[#D8C4B6]/40 p-5">
                    <div>
                        <h3 class="text-base font-semibold text-[#1b334e]">Ringkasan Kas</h3>
                        <p class="mt-0.5 text-xs text-gray-600">Status pembayaran kas anggota litbang</p>
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
</div>
@endsection