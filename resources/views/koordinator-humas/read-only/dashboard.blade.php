@extends('layouts.koordinator-humas')

@section('title', 'Dashboard Koordinator Jurnalistik')
@section('header', 'Dashboard Koordinator Jurnalistik (Read-Only)')

@section('content')
<div class="space-y-6">
    {{-- Read-Only Banner --}}
    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-6 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold tracking-tight text-[#1b334e] sm:text-3xl">
                    Dashboard Koordinator Jurnalistik
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Lihat aktivitas UKM Jurnalistik (Read-Only)
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                    <i class="fas fa-calendar text-[#f9b61a]"></i>
                    <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-[#f9b61a]/10">
                    <i class="fas fa-chart-pie text-[#f9b61a] text-4xl"></i>
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
                        <i class="fas fa-eye"></i>
                        {{ number_format($totalViews) }} views
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-newspaper text-xl"></i>
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
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-users text-xl"></i>
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
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-image text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Briefs --}}
        <div class="group relative overflow-hidden rounded-xl border border-[#D8C4B6]/40 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-[#1b334e]">{{ $totalBriefs }}</p>
                    <p class="mt-1.5 text-xs text-gray-500">Brief Litbang</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f9b61a]/10 text-[#f9b61a]">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Financial Overview --}}
    @if(isset($totalPemasukan) && isset($totalPengeluaran))
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
            <h3 class="text-base font-semibold text-[#1b334e]">Ringkasan Keuangan</h3>
        </div>
        <div class="p-5">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-[#D8C4B6]/40 p-4">
                    <p class="text-xs font-medium text-gray-500">Total Pemasukan</p>
                    <p class="mt-1 text-xl font-bold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg border border-[#D8C4B6]/40 p-4">
                    <p class="text-xs font-medium text-gray-500">Total Pengeluaran</p>
                    <p class="mt-1 text-xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg border border-[#D8C4B6]/40 p-4">
                    <p class="text-xs font-medium text-gray-500">Saldo</p>
                    <p class="mt-1 text-xl font-bold text-[#1b334e]">Rp {{ number_format($saldo ?? ($totalPemasukan - $totalPengeluaran), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent News --}}
    @if(isset($recentNews) && $recentNews->count() > 0)
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
        <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
            <h3 class="text-base font-semibold text-[#1b334e]">Berita Terbaru</h3>
        </div>
        <div class="p-5">
            <div class="space-y-3">
                @foreach($recentNews->take(5) as $item)
                <div class="flex items-center gap-3 rounded-lg border border-[#D8C4B6]/40 p-3 hover:bg-[#f9b61a]/5 transition-colors">
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="h-12 w-12 rounded-lg object-cover">
                    @else
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-[#1b334e] truncate">{{ $item->title }}</p>
                        <p class="text-xs text-gray-500">{{ $item->created_at->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('koordinator-humas.view.news.show', $item->id) }}" class="text-[#1b334e] hover:text-[#f9b61a]">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
