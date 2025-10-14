@extends('layouts.koordinator-jurnalistik')

@section('title', 'Tambah Brief Berita')
@section('header', 'Tambah Brief Berita')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Tambah Brief Berita Baru</h3>
                <a href="{{ route('koordinator-jurnalistik.briefs.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>

        <form action="{{ route('koordinator-jurnalistik.briefs.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Brief</h4>
                
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul Brief <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('judul') border-red-300 @enderror" required>
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('tanggal') border-red-300 @enderror" required>
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="isi_brief" class="block text-sm font-medium text-gray-700">Isi Brief <span class="text-red-500">*</span></label>
                    <textarea name="isi_brief" id="isi_brief" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('isi_brief') border-red-300 @enderror" placeholder="Tulis isi brief berita di sini..." required>{{ old('isi_brief') }}</textarea>
                    @error('isi_brief')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="link_referensi" class="block text-sm font-medium text-gray-700">Link Referensi</label>
                    <textarea name="link_referensi" id="link_referensi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('link_referensi') border-red-300 @enderror" placeholder="Masukkan link referensi (pisahkan dengan enter untuk multiple link)">{{ old('link_referensi') }}</textarea>
                    @error('link_referensi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('koordinator-jurnalistik.briefs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Brief
                </button>
            </div>
        </form>
    </div>
</div>
@endsection