@extends('layouts.sekretaris')

@section('title', 'Detail Notulensi')
@section('header', 'Detail Notulensi Rapat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $notulensi->judul }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-calendar mr-1"></i>{{ $notulensi->tanggal->format('d F Y') }}
                        <span class="ml-4">
                            <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($notulensi->waktu_mulai)->format('H:i') }}
                            @if($notulensi->waktu_selesai)
                                - {{ \Carbon\Carbon::parse($notulensi->waktu_selesai)->format('H:i') }}
                            @endif
                        </span>
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('sekretaris.notulensi.edit', $notulensi) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('sekretaris.notulensi.destroy', $notulensi) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus notulensi ini?')">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            @if($notulensi->tempat)
            <div>
                <p class="text-sm font-medium text-gray-700">Tempat</p>
                <p class="text-gray-900">{{ $notulensi->tempat }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm font-medium text-gray-700">Dibuat oleh</p>
                <p class="text-gray-900">{{ $notulensi->creator->name }}</p>
            </div>
        </div>

        <!-- Peserta -->
        @if($notulensi->peserta)
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 mb-2">Peserta</p>
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-gray-900 whitespace-pre-line">{{ $notulensi->peserta }}</p>
            </div>
        </div>
        @endif

        <!-- Isi Notulensi -->
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 mb-2">Isi Notulensi</p>
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-gray-900 whitespace-pre-line">{{ $notulensi->isi_notulensi }}</p>
            </div>
        </div>

        <!-- Kesimpulan -->
        @if($notulensi->kesimpulan)
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 mb-2">Kesimpulan</p>
            <div class="bg-blue-50 rounded-md p-4">
                <p class="text-gray-900 whitespace-pre-line">{{ $notulensi->kesimpulan }}</p>
            </div>
        </div>
        @endif

        <!-- Tindak Lanjut: dihapus sesuai permintaan -->

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('sekretaris.notulensi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection

