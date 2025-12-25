@extends('layouts.koordinator-jurnalistik')

@section('title', 'Tambah Brief Humas')
@section('header', 'Tambah Brief Humas')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('koordinator-jurnalistik.brief-humas.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    @error('judul')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Link Drive</label>
                    <input type="url" name="link_drive" value="{{ old('link_drive') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    @error('link_drive')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Catatan</label>
                    <textarea name="catatan" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Opsional">{{ old('catatan') }}</textarea>
                    @error('catatan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('koordinator-jurnalistik.brief-humas.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection