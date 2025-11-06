@extends('layouts.koordinator-jurnalistik')

@section('title', 'Edit Caption')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Edit Caption
                </h1>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('koordinator-jurnalistik.contents.update', $content) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <!-- Basic Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Informasi Dasar
                            </h3>
                            
                            <!-- Judul Caption -->
                            <div class="mb-4">
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Caption</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror" 
                                       id="judul" name="judul" value="{{ old('judul', $content->judul) }}" 
                                       placeholder="Masukkan judul caption (opsional)">
                                @error('judul')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Caption -->
                            <div class="mb-4">
                                <label for="jenis_konten" class="block text-sm font-medium text-gray-700 mb-1">Jenis Caption <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('jenis_konten') border-red-500 @enderror" 
                                        id="jenis_konten" name="jenis_konten" required>
                                    <option value="">Pilih Jenis Caption</option>
                                    @foreach(App\Models\Content::getCaptionTypes() as $key => $label)
                                        <option value="{{ $key }}" {{ old('jenis_konten', $content->jenis_konten) === $key ? 'selected' : '' }}>
                                            {{ $label }}
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
                                    @foreach($berita ?? [] as $news)
                                        <option value="{{ $news->id }}" {{ old('berita_id', $content->berita_id) == $news->id ? 'selected' : '' }}>
                                            {{ $news->judul }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('berita_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Desain Referensi (for Caption Desain) -->
                            <div class="mb-4" id="desain_group" style="display: none;">
                                <label for="desain_id" class="block text-sm font-medium text-gray-700 mb-1">Desain Referensi <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('desain_id') border-red-500 @enderror" 
                                        id="desain_id" name="desain_id">
                                    <option value="">Pilih Desain</option>
                                    @foreach($desain ?? [] as $design)
                                        <option value="{{ $design->id }}" {{ old('desain_id', $content->desain_id) == $design->id ? 'selected' : '' }}>
                                            {{ $design->judul }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('desain_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                        </div>

                        <!-- Caption Content -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-quote-left text-blue-600 mr-2"></i>
                                Caption
                            </h3>
                            
                            <div class="mb-4">
                                <label for="caption" class="block text-sm font-medium text-gray-700 mb-1">Isi Caption <span class="text-red-500">*</span></label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('caption') border-red-500 @enderror" 
                                          id="caption" name="caption" rows="6" required 
                                          placeholder="Tulis caption yang menarik dan informatif...">{{ old('caption', $content->caption) }}</textarea>
                                @error('caption')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Platform Upload -->
                            <div class="mb-4">
                                <label for="platform_upload" class="block text-sm font-medium text-gray-700 mb-1">Platform Upload</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('platform_upload') border-red-500 @enderror" 
                                        id="platform_upload" name="platform_upload">
                                    <option value="">Pilih Platform Upload (Opsional)</option>
                                    <option value="tiktok" {{ old('platform_upload', $content->platform_upload) === 'tiktok' ? 'selected' : '' }}>TikTok</option>
                                    <option value="instagram_feed" {{ old('platform_upload', $content->platform_upload) === 'instagram_feed' ? 'selected' : '' }}>Instagram Feed</option>
                                    <option value="instagram_story" {{ old('platform_upload', $content->platform_upload) === 'instagram_story' ? 'selected' : '' }}>Instagram Story</option>
                                    <option value="youtube" {{ old('platform_upload', $content->platform_upload) === 'youtube' ? 'selected' : '' }}>YouTube</option>
                                </select>
                                @error('platform_upload')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Publishing -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Publikasi</h3>
                            
                            <div class="mb-4">
                                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                                <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('published_at') border-red-500 @enderror" 
                                       id="published_at" name="published_at" 
                                       value="{{ old('published_at', $content->published_at ? $content->published_at->format('Y-m-d\TH:i') : '') }}">
                                @error('published_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-6 pt-4 border-t">
                    <a href="{{ route('koordinator-jurnalistik.contents.show', $content) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Caption
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisKontenSelect = document.getElementById('jenis_konten');
    const beritaGroup = document.getElementById('berita_group');
    const desainGroup = document.getElementById('desain_group');
    const beritaIdSelect = document.getElementById('berita_id');
    const desainIdSelect = document.getElementById('desain_id');
    const statusSelect = document.getElementById('status');
    const publishDateSection = document.getElementById('publishDateSection');

    // Handle jenis konten change
    function handleJenisKontenChange() {
        const selectedValue = jenisKontenSelect.value;
        
        if (selectedValue === 'caption_berita') {
            beritaGroup.style.display = 'block';
            desainGroup.style.display = 'none';
            beritaIdSelect.required = true;
            desainIdSelect.required = false;
        } else if (selectedValue === 'caption_desain') {
            beritaGroup.style.display = 'none';
            desainGroup.style.display = 'block';
            beritaIdSelect.required = false;
            desainIdSelect.required = true;
        } else {
            beritaGroup.style.display = 'none';
            desainGroup.style.display = 'none';
            beritaIdSelect.required = false;
            desainIdSelect.required = false;
        }
    }

    // Handle status change
    function handleStatusChange() {
        const selectedStatus = statusSelect.value;
        if (selectedStatus === 'published') {
            publishDateSection.style.display = 'block';
        } else {
            publishDateSection.style.display = 'none';
        }
    }

    // Initial setup
    handleJenisKontenChange();
    handleStatusChange();

    // Event listeners
    jenisKontenSelect.addEventListener('change', handleJenisKontenChange);
    statusSelect.addEventListener('change', handleStatusChange);
});
</script>
@endpush
@endsection