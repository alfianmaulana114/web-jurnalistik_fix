@extends('layouts.bendahara')

@section('title', 'Detail Desain')
@section('header', 'Detail Desain (Read-Only)')

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

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-[#1b334e]">{{ $design->judul }}</h2>
            <p class="mt-1 text-sm text-gray-600">Dibuat pada {{ $design->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('bendahara.view.designs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Media Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-lg font-medium text-[#1b334e]">Preview Media</h3>
                </div>
                <div class="p-6">
                    @if($design->media_url)
                        <a href="{{ $design->media_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-external-link-alt mr-2"></i>Buka Media
                        </a>
                    @else
                        <p class="text-gray-500">Tidak ada media tersedia</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40">
                <div class="px-6 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-lg font-medium text-[#1b334e]">Informasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                        @php($jenisOptions = \App\Models\Design::getJenisOptions())
                        <dd class="mt-1 text-sm text-gray-900">{{ $jenisOptions[$design->jenis] ?? $design->jenis }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Berita Terkait</dt>
                        <dd class="mt-1 text-sm text-blue-600">
                            @if($design->berita)
                                <a href="{{ route('bendahara.view.news.show', $design->berita->id) }}" class="hover:underline">{{ $design->berita->title ?? $design->berita->judul }}</a>
                            @else
                                <span class="text-gray-500">Tidak terkait berita</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $design->catatan ?: 'Tidak ada catatan' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Diperbarui</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $design->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

