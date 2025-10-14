@extends('layouts.koordinator-jurnalistik')

@section('title', 'Detail Brief Berita')
@section('header', 'Detail Brief Berita')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $brief->judul }}</h1>
            <p class="mt-1 text-sm text-gray-600">Dibuat pada {{ $brief->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('koordinator-jurnalistik.briefs.edit', $brief) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('koordinator-jurnalistik.briefs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto">
        <!-- Brief Details -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Detail Brief</h3>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $brief->tanggal ? $brief->tanggal->format('d M Y') : '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Isi Brief</label>
                    <div class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $brief->isi_brief }}</div>
                </div>

                @if($brief->link_referensi)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Link Referensi</label>
                    <div class="mt-1 text-sm text-gray-900">
                        {{ $brief->link_referensi }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
                </div>
            </div>
            @endif

            <!-- Related Content -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Konten Terkait</h3>
                        <a href="{{ route('koordinator-jurnalistik.contents.create', ['brief_id' => $brief->id]) }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Konten
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($brief->contents->count() > 0)
                    <div class="space-y-4">
                        @foreach($brief->contents as $content)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('koordinator-jurnalistik.contents.show', $content) }}" class="hover:text-red-600">
                                            {{ $content->judul }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-600 mt-1">{{ $content->getTypeLabel() }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($content->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($content->status === 'review') bg-yellow-100 text-yellow-800
                                    @elseif($content->status === 'approved') bg-green-100 text-green-800
                                    @elseif($content->status === 'published') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $content->getStatusLabel() }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-3">{{ Str::limit(strip_tags($content->konten), 100) }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center space-x-4">
                                    <span>{{ $content->creator->name }}</span>
                                    @if($content->reviewer)
                                    <span>Review: {{ $content->reviewer->name }}</span>
                                    @endif
                                </div>
                                <span>{{ $content->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 mb-4">Belum ada konten untuk brief ini</p>
                        <a href="{{ route('koordinator-jurnalistik.contents.create', ['brief_id' => $brief->id]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Konten
                        </a>
                    </div>
                    @endif
@endsection