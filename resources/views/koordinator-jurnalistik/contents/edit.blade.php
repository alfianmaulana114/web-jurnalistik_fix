@extends('layouts.koordinator-jurnalistik')

@section('title', 'Edit Caption')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Edit Caption</h3>
        
        <form action="{{ route('koordinator-jurnalistik.contents.update', $content) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="button" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-md bg-blue-600 text-white" data-lang-toggle="id">Indonesia</button>
                    <button type="button" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-r-md bg-white text-gray-700" data-lang-toggle="en">English</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Caption -->
                <div class="col-span-2" data-lang-section="id">
                    <label for="judul_id" class="block text-sm font-medium text-gray-700 mb-2">Judul Caption (Indonesia)</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('judul_id') border-red-500 @enderror" 
                           id="judul_id" name="judul_id" value="{{ old('judul_id', optional($content->translations->firstWhere('locale','id'))->judul ?? $content->judul) }}" 
                           placeholder="Masukkan judul caption (opsional)">
                    @error('judul_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2 hidden" data-lang-section="en">
                    <label for="judul_en" class="block text-sm font-medium text-gray-700 mb-2">Caption Title (English)</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('judul_en') border-red-500 @enderror" 
                           id="judul_en" name="judul_en" value="{{ old('judul_en', optional($content->translations->firstWhere('locale','en'))->judul) }}" 
                           placeholder="Enter caption title (optional)">
                    @error('judul_en')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Caption -->
                <div>
                    <label for="jenis_konten" class="block text-sm font-medium text-gray-700 mb-2">Jenis Caption <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('jenis_konten') border-red-500 @enderror" 
                            id="jenis_konten" name="jenis_konten" required>
                        <option value="">Pilih Jenis Caption</option>
                        @foreach(App\Models\Content::getCaptionTypes() as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis_konten', $content->jenis_konten) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_konten')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published At -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('published_at') border-red-500 @enderror" 
                           id="published_at" name="published_at" 
                           value="{{ old('published_at', $content->published_at ? $content->published_at->format('Y-m-d\TH:i') : '') }}">
                    @error('published_at')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Berita Referensi (for Caption Berita) -->
                <div class="col-span-2" id="berita_group" style="display: none;">
                    <label for="berita_id" class="block text-sm font-medium text-gray-700 mb-2">Berita Referensi <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('berita_id') border-red-500 @enderror" 
                            id="berita_id" name="berita_id">
                        <option value="">Pilih Berita</option>
                        @foreach($berita ?? [] as $news)
                            <option value="{{ $news->id }}" {{ old('berita_id', $content->berita_id) == $news->id ? 'selected' : '' }}>
                                {{ $news->judul }}
                            </option>
                        @endforeach
                    </select>
                    @error('berita_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Desain Referensi (for Caption Desain) -->
                <div class="col-span-2" id="desain_group" style="display: none;">
                    <label for="desain_id" class="block text-sm font-medium text-gray-700 mb-2">Desain Referensi <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('desain_id') border-red-500 @enderror" 
                            id="desain_id" name="desain_id">
                        <option value="">Pilih Desain</option>
                        @foreach($desain ?? [] as $design)
                            <option value="{{ $design->id }}" {{ old('desain_id', $content->desain_id) == $design->id ? 'selected' : '' }}>
                                {{ $design->judul }}
                            </option>
                        @endforeach
                    </select>
                    @error('desain_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Caption Content -->
                <div class="col-span-2" data-lang-section="id">
                    <label for="caption_id" class="block text-sm font-medium text-gray-700 mb-2">Isi Caption (Indonesia) <span class="text-red-500">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('caption_id') border-red-500 @enderror" 
                              id="caption_id" name="caption_id" rows="6" required 
                              placeholder="Tulis caption yang menarik dan informatif...">{{ old('caption_id', optional($content->translations->firstWhere('locale','id'))->caption ?? $content->caption) }}</textarea>
                    @error('caption_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2 hidden" data-lang-section="en">
                    <label for="caption_en" class="block text-sm font-medium text-gray-700 mb-2">Isi Caption (English) <span class="text-red-500">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('caption_en') border-red-500 @enderror" 
                              id="caption_en" name="caption_en" rows="6" required 
                              placeholder="Write a compelling and informative caption...">{{ old('caption_en', optional($content->translations->firstWhere('locale','en'))->caption) }}</textarea>
                    @error('caption_en')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Platform Upload -->
                <div class="col-span-2">
                    <label for="platform_upload" class="block text-sm font-medium text-gray-700 mb-2">Platform Upload</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('platform_upload') border-red-500 @enderror" 
                            id="platform_upload" name="platform_upload">
                        <option value="">Pilih Platform Upload (Opsional)</option>
                        <option value="tiktok" {{ old('platform_upload', $content->platform_upload) === 'tiktok' ? 'selected' : '' }}>TikTok</option>
                        <option value="instagram_feed" {{ old('platform_upload', $content->platform_upload) === 'instagram_feed' ? 'selected' : '' }}>Instagram Feed</option>
                        <option value="instagram_story" {{ old('platform_upload', $content->platform_upload) === 'instagram_story' ? 'selected' : '' }}>Instagram Story</option>
                        <option value="youtube" {{ old('platform_upload', $content->platform_upload) === 'youtube' ? 'selected' : '' }}>YouTube</option>
                    </select>
                    @error('platform_upload')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('koordinator-jurnalistik.contents.show', $content) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
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
    if (statusSelect) {
        handleStatusChange();
        statusSelect.addEventListener('change', handleStatusChange);
    }

    // Event listeners
    jenisKontenSelect.addEventListener('change', handleJenisKontenChange);
    const toggleButtons = document.querySelectorAll('[data-lang-toggle]');
    const sections = document.querySelectorAll('[data-lang-section]');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const lang = btn.getAttribute('data-lang-toggle');
            toggleButtons.forEach(b => {
                b.classList.remove('bg-blue-600','text-white');
                b.classList.add('bg-white','text-gray-700');
            });
            btn.classList.add('bg-blue-600','text-white');
            btn.classList.remove('bg-white','text-gray-700');
            sections.forEach(sec => {
                sec.classList.toggle('hidden', sec.getAttribute('data-lang-section') !== lang);
            });
        });
    });
    
    // Double click protection
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;
    
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });
});
</script>
@endpush
@endsection