@extends('layouts.koordinator-litbang')

@section('title', 'Detail Brief')
@section('header', 'Detail Brief Divisi Litbang')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $brief->judul }}</h1>
                <p class="text-sm text-gray-600">{{ $brief->tanggal ? $brief->tanggal->format('d M Y') : '-' }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('koordinator-litbang.briefs.edit', $brief) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600"><i class="fas fa-edit mr-2"></i>Edit</a>
                <a href="{{ route('koordinator-litbang.briefs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
            </div>
        </div>
        <div class="mt-6 space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Isi Brief</h3>
                <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $brief->isi_brief }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Link Referensi</h3>
                @if($brief->link_referensi)
                    <textarea readonly onclick="this.select()" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-2 py-1 text-sm cursor-pointer resize-none">{{ $brief->link_referensi }}</textarea>
                @else
                    <p class="text-gray-500">-</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection