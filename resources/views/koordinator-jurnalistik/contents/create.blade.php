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
                        <!-- Judul Caption (Optional) -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Caption</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" 
                                   placeholder="Masukkan judul caption (opsional)">
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

                        <!-- Berita Referensi (for Caption Berita) -->
                        <div class="mb-4" id="berita_group" style="display: none;">
                            <label for="berita_id" class="block text-sm font-medium text-gray-700 mb-1">Berita Referensi <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('berita_id') border-red-500 @enderror" 
                                    id="berita_id" name="berita_id">
                                <option value="">Pilih Berita</option>
                                @foreach($beritas as $berita)
                                    <option value="{{ $berita->id }}" {{ old('berita_id') == $berita->id ? 'selected' : '' }} {{ $selectedNews && $selectedNews->id == $berita->id ? 'selected' : '' }}>
                                        {{ $berita->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('berita_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Desain Referensi (for Caption Media Kreatif) -->
                        <div class="mb-4" id="desain_group" style="display: none;">
                            <label for="desain_id" class="block text-sm font-medium text-gray-700 mb-1">Desain Referensi <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('desain_id') border-red-500 @enderror" 
                                    id="desain_id" name="desain_id">
                                <option value="">Pilih Desain</option>
                                @foreach($desains as $desain)
                                    <option value="{{ $desain->id }}" {{ old('desain_id') == $desain->id ? 'selected' : '' }}>
                                        {{ $desain->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('desain_id')
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

                        <!-- Platform Upload -->
                        <div class="mb-4">
                            <label for="platform_upload" class="block text-sm font-medium text-gray-700 mb-1">Platform Upload</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('platform_upload') border-red-500 @enderror" 
                                    id="platform_upload" name="platform_upload">
                                <option value="">Pilih Platform Upload</option>
                                <option value="tiktok" {{ old('platform_upload') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                <option value="instagram_feed" {{ old('platform_upload') == 'instagram_feed' ? 'selected' : '' }}>Instagram Feed</option>
                                <option value="instagram_story" {{ old('platform_upload') == 'instagram_story' ? 'selected' : '' }}>Instagram Story</option>
                                <option value="youtube" {{ old('platform_upload') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                            </select>
                            <p class="text-gray-500 text-xs mt-1">Pilih platform untuk mengupload caption ini.</p>
                            @error('platform_upload')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>




                    </div>

                    <div class="lg:col-span-1">
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
    const beritaGroup = document.getElementById('berita_group');
    const desainGroup = document.getElementById('desain_group');
    
    if (jenisKonten === 'caption_berita') {
        beritaGroup.style.display = 'block';
        desainGroup.style.display = 'none';
    } else if (jenisKonten === 'caption_media_kreatif') {
        beritaGroup.style.display = 'none';
        desainGroup.style.display = 'block';
    } else {
        beritaGroup.style.display = 'none';
        desainGroup.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleMediaFields();
    
    // Auto-select caption_berita and show berita group if news is selected
    @if($selectedNews)
        document.getElementById('jenis_konten').value = 'caption_berita';
        toggleMediaFields();
    @endif
});
</script>
@endsection