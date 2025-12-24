@extends('layouts.koordinator-media-kreatif')

@section('title', 'Dashboard')
@section('header', 'Dashboard Koordinator Media Kreatif')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-pink-100 mt-1">Kelola aktivitas Divisi Media Kreatif</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-palette text-6xl text-pink-200"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Anggota Media Kreatif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $users->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-palette text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Desain</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $design_total }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-closed-captioning text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Caption</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $captions_total }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kas Lunas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $kas_lunas }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-palette mr-2 text-pink-600"></i>Desain Terbaru
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($designs as $design)
                    <div class="flex items-start space-x-3">
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 line-clamp-2">{{ $design->judul }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $design->created_at->diffForHumans() }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-800">{{ ucfirst($design->status ?? 'draft') }}</span>
                                @if($design->berita)
                                <i class="fas fa-newspaper ml-3 mr-1"></i>
                                {{ $design->berita->title }}
                                @endif
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

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>Ringkasan Kas Anggota
                    </h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded p-4">
                        <p class="text-sm text-gray-600">Total Catatan Kas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $kas_total_records }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-4">
                        <p class="text-sm text-gray-600">Belum Lunas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $kas_belum_lunas }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-4 col-span-2">
                        <p class="text-sm text-gray-600">Total Terkumpul</p>
                        <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($kas_total_terkumpul, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection