{{-- Shadcn-Inspired Dashboard - Koordinator Jurnalistik --}}
@extends('layouts.koordinator-jurnalistik')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Jurnalistik')

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
                    Kelola aktivitas UKM Jurnalistik dengan mudah dan efisien
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
                    <svg class="h-12 w-12 text-[#f9b61a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total News --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $totalNews }}</p>
                    <p class="mt-1.5 flex items-center gap-1 text-xs text-gray-500">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ number_format($totalViews) }} views
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $totalUsers }}</p>
                    <p class="mt-1.5 text-xs text-gray-500">Semua divisi</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Contents --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Konten Aktif</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $totalContents + $totalDesigns }}</p>
                    <p class="mt-1.5 text-xs text-gray-500">Caption & Desain</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Briefs --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $totalBriefs }}</p>
                    <p class="mt-1.5 flex items-center gap-1 text-xs text-gray-500">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        {{ $totalFunfacts }} funfacts
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview: Total Saldo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Saldo</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kas + Pemasukan - Pengeluaran</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a] transition-colors group-hover:bg-[#f9b61a] group-hover:text-white">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm mt-4">
        <div class="border-b border-[#D8C4B6]/40 p-5">
            <div>
                <h3 class="text-base font-semibold text-[#1b334e]">Grafik Pemasukan & Pengeluaran</h3>
                <p class="mt-0.5 text-xs text-gray-600">Pemasukan (kas + pemasukan) dibanding pengeluaran</p>
            </div>
        </div>
        <div class="p-5">
            <div class="h-64">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    </div>
    

    

    {{-- Proker & Briefs --}}
    <div class="grid gap-4 lg:grid-cols-2">
        {{-- Proker --}}
        <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-5">
                <h3 class="text-base font-semibold text-[#1b334e]">Status Program Kerja</h3>
            </div>
            <div class="p-5">
                <div class="mb-4 grid grid-cols-3 gap-3">
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-center">
                        <p class="text-xl font-bold text-gray-900">{{ $prokerStats['total'] }}</p>
                        <p class="mt-0.5 text-xs text-gray-600">Total</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-center">
                        <p class="text-xl font-bold text-blue-600">{{ $prokerStats['active'] }}</p>
                        <p class="mt-0.5 text-xs text-gray-600">Aktif</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-center">
                        <p class="text-xl font-bold text-green-600">{{ $prokerStats['completed'] }}</p>
                        <p class="mt-0.5 text-xs text-gray-600">Selesai</p>
                    </div>
                </div>
                
                <div>
                    <h4 class="mb-2.5 text-xs font-semibold text-gray-900">Proker Terbaru</h4>
                    <div class="space-y-2">
                        @forelse($recentProkers as $proker)
                        <a href="{{ route('koordinator-jurnalistik.prokers.show', $proker) }}" class="block rounded-lg border border-[#D8C4B6]/40 bg-white p-3 shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $proker->nama_proker }}</p>
                                    <p class="text-xs text-gray-500">{{ $proker->tanggal_mulai->format('d M Y') }}</p>
                                </div>
                                <span class="ml-3 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                    {{ ucfirst($proker->status) }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="rounded-lg border border-dashed border-gray-200 p-8 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada proker</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    {{-- Recent News --}}
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 p-6">
            <div>
                <h3 class="text-lg font-semibold text-[#1b334e]">Berita Terbaru</h3>
                <p class="mt-1 text-sm text-gray-600">Publikasi terbaru dari tim redaksi</p>
            </div>
            <a href="{{ route('koordinator-jurnalistik.news.index') }}" class="text-sm font-medium text-[#1b334e] hover:text-[#f9b61a]">
                Lihat Semua →
            </a>
        </div>
        <div class="p-6">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($recentNews as $news)
                <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="group block overflow-hidden rounded-lg border border-[#D8C4B6]/40 bg-white shadow-sm transition-all hover:shadow-md">
                    @if($news->image)
                    <div class="aspect-video overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="h-full w-full object-cover transition-transform group-hover:scale-105">
                    </div>
                    @else
                    <div class="aspect-video bg-gradient-to-br from-[#1b334e] to-[#2a4a6e]"></div>
                    @endif
                    <div class="p-4">
                        <h4 class="line-clamp-2 font-semibold text-gray-900 group-hover:text-[#f9b61a]">{{ $news->title }}</h4>
                        <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                            <span>{{ number_format($news->views) }} views</span>
                            <span>{{ $news->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full rounded-lg border border-dashed border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Belum ada berita</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal: Unpaid Kas Details --}}
<div id="unpaidKasModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeUnpaidKasModal()"></div>
        
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:align-middle">
            {{-- Header --}}
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-[#1b334e]">Detail Kas Belum Lunas</h3>
                        <p class="mt-1 text-sm text-gray-600">Daftar anggota yang belum menyelesaikan pembayaran kas</p>
                    </div>
                    <button onclick="closeUnpaidKasModal()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{-- Search & Filter --}}
                <div class="mb-4 flex gap-3">
                    <input type="text" id="kasSearchInput" placeholder="Cari nama anggota..." class="flex-1 rounded-lg border border-gray-200 px-4 py-2 text-sm focus:border-[#f9b61a] focus:outline-none focus:ring-2 focus:ring-[#f9b61a]/20">
                    <select id="kasStatusFilter" class="rounded-lg border border-gray-200 px-4 py-2 text-sm focus:border-[#f9b61a] focus:outline-none focus:ring-2 focus:ring-[#f9b61a]/20">
                        <option value="">Semua Status</option>
                        <option value="belum_bayar">Belum Bayar</option>
                        <option value="sebagian">Sebagian</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                    <button type="button" onclick="applyKasFilter()" class="inline-flex items-center gap-2 rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] hover:bg-[#f9b61a]/10 transition-all">
                        <i class="fas fa-search"></i>
                        Filter
                    </button>
                </div>

                @if($unpaidKasMembers->count() === 0)
                <div class="rounded-lg border border-dashed border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-gray-900">Semua Anggota Sudah Lunas</p>
                    <p class="mt-1 text-sm text-gray-500">Tidak ada pembayaran kas yang tertunda</p>
                </div>
                @else
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="max-h-96 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="sticky top-0 bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Anggota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Terbayar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white" id="kasTableBody">
                                @foreach($unpaidKasMembers as $kas)
                                <tr class="kas-row hover:bg-gray-50" data-status="{{ $kas->status_pembayaran }}">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white">
                                                <span class="text-sm font-semibold">{{ strtoupper(substr($kas->user->name ?? '?', 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="kas-name text-sm font-medium text-gray-900">{{ $kas->user->name ?? '-' }}</div>
                                                <div class="text-sm text-gray-500">{{ $kas->user->nim ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ ucfirst($kas->periode) }} {{ $kas->tahun }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if($kas->status_pembayaran === 'belum_bayar')
                                            <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">Belum Bayar</span>
                                        @elseif($kas->status_pembayaran === 'sebagian')
                                            <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">Sebagian</span>
                                        @elseif($kas->status_pembayaran === 'terlambat')
                                            <span class="inline-flex rounded-full bg-orange-100 px-2 py-1 text-xs font-semibold text-orange-800">Terlambat</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($kas->jumlah_terbayar, 0, ',', '.') }}</div>
                                        @php
                                            $standardAmount = \App\Models\KasAnggota::getStandardAmount();
                                            $percentage = $standardAmount > 0 ? ($kas->jumlah_terbayar / $standardAmount) * 100 : 0;
                                        @endphp
                                        <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-gray-200">
                                            <div class="h-1.5 rounded-full bg-blue-600" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">{{ number_format($percentage, 0) }}%</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4 rounded-lg border border-[#f9b61a]/20 bg-[#f9b61a]/5 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Total: {{ $unpaidKasMembers->count() }} anggota belum lunas</span>
                        <span class="text-sm font-semibold text-gray-900">
                            Outstanding: Rp {{ number_format($unpaidKasMembers->sum(function($kas) { 
                                return \App\Models\KasAnggota::getStandardAmount() - $kas->jumlah_terbayar; 
                            }), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                <div class="flex justify-end">
                    <button onclick="closeUnpaidKasModal()" class="rounded-lg border border-[#D8C4B6]/40 bg-white px-4 py-2 text-sm font-medium text-[#1b334e] shadow-sm transition-all hover:shadow-md hover:bg-[#f9b61a]/10">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const financialCtx = document.getElementById('financialChart').getContext('2d');
new Chart(financialCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels'] ?? []) !!},
        datasets: [
            {
                label: 'Pemasukan (Kas + Pemasukan)',
                data: {!! json_encode($chartData['income_combined'] ?? []) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4
            },
            {
                label: 'Pengeluaran',
                data: {!! json_encode($chartData['pengeluaran'] ?? []) !!},
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: { color: '#374151', padding: 16 }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: ctx => `${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString('id-ID')}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                border: { display: false },
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: {
                    color: '#6B7280',
                    callback: v => 'Rp ' + (v / 1000) + 'k'
                }
            },
            x: {
                border: { display: false },
                grid: { display: false },
                ticks: { color: '#6B7280' }
            }
        }
    }
});

// Modal functions
function openUnpaidKasModal() {
    document.getElementById('unpaidKasModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUnpaidKasModal() {
    document.getElementById('unpaidKasModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Kas filtering - hanya bekerja saat tombol Filter ditekan
function applyKasFilter() {
    const kasSearchInput = document.getElementById('kasSearchInput');
    const kasStatusFilter = document.getElementById('kasStatusFilter');
    
    if (!kasSearchInput || !kasStatusFilter) return;
    
    const searchTerm = (kasSearchInput.value || '').toLowerCase();
    const statusFilter = kasStatusFilter.value || '';
    const rows = document.querySelectorAll('.kas-row');
    
    rows.forEach(row => {
        const name = (row.querySelector('.kas-name')?.textContent || '').toLowerCase();
        const status = row.dataset.status || '';
        const matchesSearch = !searchTerm || name.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
}

// Close on ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeUnpaidKasModal();
});
</script>
@endpush
