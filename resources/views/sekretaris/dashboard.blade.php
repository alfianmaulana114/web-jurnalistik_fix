{{-- Dashboard Sekretaris
    - Ringkasan administrasi: notulensi, proker, absen
    - Navigasi cepat untuk operasional sekretariat
--}}
@extends('layouts.sekretaris')

@section('title', 'Dashboard')
@section('header', 'Dashboard Sekretaris')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100 mt-1">Kelola administrasi UKM Jurnalistik dengan mudah</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-clipboard text-6xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Anggota</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total News -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_news'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Prokers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Proker</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_prokers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Notulensi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Notulensi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_notulensi'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Divisi Statistics -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Statistik Per Divisi</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($divisiStats as $key => $data)
                <div class="text-center p-4 border rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $data['total'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ $data['nama'] }}</div>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent News -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Berita Terbaru</h3>
            </div>
            <div class="p-6">
                @forelse($recentActivities['news'] as $news)
                <div class="mb-4 pb-4 border-b last:border-0 last:pb-0">
                    <h4 class="font-medium text-gray-900">{{ $news->title }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ $news->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Tidak ada berita</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Prokers -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Program Kerja Terbaru</h3>
            </div>
            <div class="p-6">
                @forelse($recentActivities['prokers'] as $proker)
                <div class="mb-4 pb-4 border-b last:border-0 last:pb-0">
                    <h4 class="font-medium text-gray-900">{{ $proker->nama_proker }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ $proker->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Tidak ada proker</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

