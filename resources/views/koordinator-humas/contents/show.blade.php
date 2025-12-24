@extends('layouts.koordinator-humas')

@section('title', 'Detail Content')
@section('header', 'Detail Content')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-[#1b334e]">{{ $content->judul ?? 'Tanpa Judul' }}</h2>
            <p class="mt-1 text-sm text-gray-600">Dibuat pada {{ $content->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('koordinator-humas.contents.edit', $content) }}" class="inline-flex items-center px-4 py-2 border border-[#D8C4B6]/40 rounded-lg shadow-sm text-sm font-medium text-[#1b334e] bg-white hover:bg-[#f9b61a]/10 transition-all">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('koordinator-humas.contents.destroy', $content) }}" method="POST" onsubmit="return confirm('Hapus content ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-all">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
                <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-base font-semibold text-[#1b334e]">Caption</h3>
                </div>
                <div class="p-5">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $content->caption }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-6">
            <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm">
                <div class="px-5 py-4 border-b border-[#D8C4B6]/40">
                    <h3 class="text-base font-semibold text-[#1b334e]">Informasi</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Jenis Content</dt>
                        <dd class="mt-1 text-sm text-[#1b334e]">Caption Media Kreatif</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Desain Terkait</dt>
                        <dd class="mt-1 text-sm text-[#1b334e]">
                            @if($content->desain)
                                <a href="#" class="hover:text-[#f9b61a]">{{ $content->desain->judul }}</a>
                            @else
                                <span class="text-gray-500">Tidak ada desain terkait</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Platform Upload</dt>
                        <dd class="mt-1 text-sm text-[#1b334e]">{{ $content->platform_upload ? ucfirst(str_replace('_', ' ', $content->platform_upload)) : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Tanggal Publikasi</dt>
                        <dd class="mt-1 text-sm text-[#1b334e]">{{ $content->published_at ? $content->published_at->format('d M Y, H:i') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Pembuat</dt>
                        <dd class="mt-1 text-sm text-[#1b334e]">{{ $content->creator->name ?? 'Unknown' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Diperbarui</dt>
                        <dd class="mt-1 text-sm text-[#1b334e]">{{ $content->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

