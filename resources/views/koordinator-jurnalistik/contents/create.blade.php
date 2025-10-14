@extends('layouts.koordinator-jurnalistik')

@section('title', 'Buat Caption Baru')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-6xl">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Buat Caption Baru</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('koordinator-jurnalistik.contents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <!-- Judul Caption -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Caption <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" 
                                   placeholder="Masukkan judul caption">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Caption -->
                        <div class="mb-4">
                            <label for="jenis_konten" class="block text-sm font-medium text-gray-700 mb-1">Jenis Caption <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('jenis_konten') border-red-500 @enderror" 
                                    id="jenis_konten" name="jenis_konten" onchange="toggleMediaFields()">
                                <option value="">Pilih Jenis Caption</option>
                                @foreach(App\Models\Content::getCaptionTypes() as $key => $value)
                                    <option value="{{ $key }}" {{ old('jenis_konten') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_konten')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Media Type (for Media Kreatif) -->
                        <div class="mb-4" id="media_type_group" style="display: none;">
                            <label for="media_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Media</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('media_type') border-red-500 @enderror" 
                                    id="media_type" name="media_type">
                                <option value="">Pilih Tipe Media</option>
                                @foreach(App\Models\Content::getMediaTypes() as $key => $value)
                                    <option value="{{ $key }}" {{ old('media_type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('media_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Media File Upload -->
                        <div class="mb-4" id="media_file_group" style="display: none;">
                            <label for="media_file" class="block text-sm font-medium text-gray-700 mb-1">File Media</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('media_file') border-red-500 @enderror" 
                                   id="media_file" name="media_file" accept="image/*,video/*">
                            <p class="text-gray-500 text-xs mt-1">
                                Upload foto (JPG, PNG, GIF) atau video (MP4, MOV, AVI). Maksimal 20MB.
                            </p>
                            @error('media_file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Media Description -->
                        <div class="mb-4" id="media_description_group" style="display: none;">
                            <label for="media_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Media</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('media_description') border-red-500 @enderror" 
                                      id="media_description" name="media_description" rows="3" 
                                      placeholder="Deskripsi singkat tentang media">{{ old('media_description') }}</textarea>
                            @error('media_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Referensi Berita (for Caption Berita) -->
                        <div class="mb-4" id="berita_referensi_group" style="display: none;">
                            <label for="berita_referensi" class="block text-sm font-medium text-gray-700 mb-1">Referensi Berita</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('berita_referensi') border-red-500 @enderror" 
                                      id="berita_referensi" name="berita_referensi" rows="3" 
                                      placeholder="Link atau referensi berita yang dijadikan dasar caption">{{ old('berita_referensi') }}</textarea>
                            @error('berita_referensi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Caption Content -->
                        <div class="mb-4">
                            <label for="caption" class="block text-sm font-medium text-gray-700 mb-1">Caption <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('caption') border-red-500 @enderror" 
                                      id="caption" name="caption" rows="6" 
                                      placeholder="Tulis caption yang menarik dan informatif...">{{ old('caption') }}</textarea>
                            <p class="text-gray-500 text-xs mt-1">Tulis caption yang menarik dan informatif. Maksimal 1000 karakter.</p>
                            @error('caption')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sumber -->
                        <div class="mb-4">
                            <label for="sumber" class="block text-sm font-medium text-gray-700 mb-1">Sumber</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('sumber') border-red-500 @enderror" 
                                   id="sumber" name="sumber" value="{{ old('sumber') }}" 
                                   placeholder="Sumber informasi atau media">
                            @error('sumber')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan Editor -->
                        <div class="mb-4">
                            <label for="catatan_editor" class="block text-sm font-medium text-gray-700 mb-1">Catatan Editor</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('catatan_editor') border-red-500 @enderror" 
                                      id="catatan_editor" name="catatan_editor" rows="3" 
                                      placeholder="Catatan khusus untuk editor">{{ old('catatan_editor') }}</textarea>
                            @error('catatan_editor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <!-- Brief -->
                        <div class="mb-4">
                            <label for="brief_id" class="block text-sm font-medium text-gray-700 mb-1">Brief Terkait</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('brief_id') border-red-500 @enderror" 
                                    id="brief_id" name="brief_id">
                                <option value="">Pilih Brief (Opsional)</option>
                                @foreach($briefs as $brief)
                                    <option value="{{ $brief->id }}" {{ old('brief_id') == $brief->id ? 'selected' : '' }}>
                                        {{ $brief->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brief_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                                    id="status" name="status">
                                @foreach(App\Models\Content::getAllStatuses() as $key => $value)
                                    <option value="{{ $key }}" {{ old('status', 'draft') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reviewer -->
                        <div class="mb-4">
                            <label for="reviewed_by" class="block text-sm font-medium text-gray-700 mb-1">Reviewer</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('reviewed_by') border-red-500 @enderror" 
                                    id="reviewed_by" name="reviewed_by">
                                <option value="">Pilih Reviewer (Opsional)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('reviewed_by') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reviewed_by')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Published At -->
                        <div class="mb-4">
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                            <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('published_at') border-red-500 @enderror" 
                                   id="published_at" name="published_at" value="{{ old('published_at') }}">
                            @error('published_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-6 pt-4 border-t">
                    <a href="{{ route('koordinator-jurnalistik.contents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Caption
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleMediaFields() {
    const jenisKonten = document.getElementById('jenis_konten').value;
    const mediaTypeGroup = document.getElementById('media_type_group');
    const mediaFileGroup = document.getElementById('media_file_group');
    const mediaDescriptionGroup = document.getElementById('media_description_group');
    const beritaReferensiGroup = document.getElementById('berita_referensi_group');
    
    if (jenisKonten === 'caption_media_kreatif') {
        mediaTypeGroup.style.display = 'block';
        mediaFileGroup.style.display = 'block';
        mediaDescriptionGroup.style.display = 'block';
        beritaReferensiGroup.style.display = 'none';
    } else if (jenisKonten === 'caption_berita') {
        mediaTypeGroup.style.display = 'none';
        mediaFileGroup.style.display = 'none';
        mediaDescriptionGroup.style.display = 'none';
        beritaReferensiGroup.style.display = 'block';
    } else {
        mediaTypeGroup.style.display = 'none';
        mediaFileGroup.style.display = 'none';
        mediaDescriptionGroup.style.display = 'none';
        beritaReferensiGroup.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleMediaFields();
});
</script>
@endsection