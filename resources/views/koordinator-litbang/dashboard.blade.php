{{-- Dashboard Koordinator Litbang
    - Ringkasan brief dan penjadwalan anggota litbang
    - Monitoring progres riset dan ide konten
--}}
@extends('layouts.koordinator-litbang')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Litbang')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-green-100 mt-1">Kelola aktivitas Divisi Litbang fokus pada Brief</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-flask text-6xl text-green-200"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Anggota Litbang</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $litbang_total }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $litbang_coordinators }} Koordinator, {{ $litbang_members }} Anggota</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Brief</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $brief_total }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $brief_with_ref }} dengan referensi, {{ $brief_without_ref }} tanpa referensi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Brief Bulan Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $brief_this_month }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rata-rata Brief/Minggu</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format(($brief_total ?? 0) / max(1, now()->weekOfYear), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>Brief Terbaru
                    </h3>
                    <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                        {{ $brief_total }} Total
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recent_briefs as $brief)
                    <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg border">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $brief->judul ?? 'Tanpa Judul' }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($brief->isi_brief ?? '', 60) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-link mr-1"></i>
                                {{ $brief->link_referensi ? Str::limit($brief->link_referensi, 40) : 'Tidak ada referensi' }}
                                <i class="fas fa-clock ml-3 mr-1"></i>
                                {{ optional($brief->tanggal)->format('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada brief</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-chart-pie mr-2 text-green-600"></i>Statistik Brief
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $brief_with_ref }}</p>
                        <p class="text-sm text-gray-600">Dengan Referensi</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ $brief_without_ref }}</p>
                        <p class="text-sm text-gray-600">Tanpa Referensi</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $brief_this_month }}</p>
                        <p class="text-sm text-gray-600">Bulan Ini</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ $brief_total }}</p>
                        <p class="text-sm text-gray-600">Total Brief</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection