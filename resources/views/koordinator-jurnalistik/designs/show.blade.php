@extends('layouts.koordinator-jurnalistik')

@section('title', 'Detail Desain - ' . $design->judul)
@section('header', 'Detail Desain')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $design->judul }}</h2>
            <p class="mt-1 text-sm text-gray-600">Dibuat pada {{ $design->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('koordinator-jurnalistik.designs.edit', $design) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('koordinator-jurnalistik.designs.destroy', $design) }}" method="POST" onsubmit="return confirm('Hapus desain ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Media Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Preview Media</h3>
                </div>
                <div class="p-6">
                    @if(method_exists($design, 'isImage') && $design->isImage())
                        <img src="{{ $design->media_url }}" alt="{{ $design->judul }}" class="w-full rounded-md border border-gray-200">
                    @elseif(method_exists($design, 'isFunfact') && $design->isFunfact())
                        <div class="bg-purple-50 border border-purple-200 rounded-md p-6">
                            <div class="text-center">
                                <i class="fas fa-lightbulb text-purple-600 text-4xl mb-4"></i>
                                <p class="text-purple-800 font-medium">{{ $design->catatan ?? 'Funfact' }}</p>
                                @if($design->media_url)
                                    <a href="{{ $design->media_url }}" target="_blank" class="mt-4 inline-flex items-center px-4 py-2 border border-purple-300 rounded-md shadow-sm text-sm font-medium text-purple-700 bg-white hover:bg-purple-50">
                                        <i class="fas fa-external-link-alt mr-2"></i>Buka Media
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <a href="{{ $design->media_url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-external-link-alt mr-2"></i>Buka Media
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi</h3>
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
                                <a href="{{ route('koordinator-jurnalistik.news.show', $design->berita->id) }}" class="hover:underline">{{ $design->berita->title }}</a>
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