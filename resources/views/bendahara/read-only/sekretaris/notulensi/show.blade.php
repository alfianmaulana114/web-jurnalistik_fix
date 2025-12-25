@extends('layouts.bendahara')

@section('title', 'Detail Notulensi (Read-Only)')
@section('header', 'Detail Notulensi (Read-Only)')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-lock text-yellow-600 text-xl"></i>
            <div>
                <h3 class="text-sm font-semibold text-yellow-800">Mode Read-Only</h3>
                <p class="text-xs text-yellow-700">Anda hanya dapat melihat data tanpa dapat melakukan perubahan</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1b334e]">{{ $notulensi->judul }}</h1>
            <p class="mt-1 text-sm text-gray-600">Dilaksanakan pada {{ $notulensi->tanggal->format('d M Y') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('bendahara.view.sekretaris.notulensi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow border border-[#D8C4B6]/40 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Tempat</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $notulensi->tempat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Dibuat Oleh</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $notulensi->creator->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        @if($notulensi->pdf_path)
        <div class="mt-6">
            <p class="text-sm font-medium text-gray-700 mb-2">Dokumen PDF</p>
            <a href="{{ route('bendahara.view.sekretaris.notulensi.download', $notulensi) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-file-pdf mr-2"></i>Unduh PDF
            </a>
        </div>
        @endif

        <div class="mt-6 bg-white rounded-lg shadow border border-[#D8C4B6]/40 p-6">
            <p class="text-sm font-medium text-gray-700 mb-2">Isi Notulensi</p>
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-gray-900 whitespace-pre-line">{{ $notulensi->isi_notulensi }}</p>
            </div>
        </div>

        @if($notulensi->kesimpulan)
        <div class="mt-6 bg-white rounded-lg shadow border border-[#D8C4B6]/40 p-6">
            <p class="text-sm font-medium text-gray-700 mb-2">Kesimpulan</p>
            <div class="bg-blue-50 rounded-md p-4">
                <p class="text-gray-900 whitespace-pre-line">{{ $notulensi->kesimpulan }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection