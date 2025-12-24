@extends('layouts.koordinator-jurnalistik')

@section('title', 'Buat Caption Baru')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Buat Caption Baru</h3>
        
        <form action="{{ route('koordinator-jurnalistik.contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="button" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-md bg-blue-600 text-white" data-lang-toggle="id">Indonesia</button>
                    <button type="button" class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-r-md bg-white text-gray-700" data-lang-toggle="en">English</button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Caption (Optional) -->
                <div class="col-span-2" data-lang-section="id">
                    <label for="judul_id" class="block text-sm font-medium text-gray-700 mb-2">Judul Caption (Indonesia)</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('judul_id') border-red-500 @enderror" 
                           id="judul_id" name="judul_id" value="{{ old('judul_id') }}" 
                           placeholder="Masukkan judul caption (opsional)">
                    @error('judul_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2 hidden" data-lang-section="en">
                    <label for="judul_en" class="block text-sm font-medium text-gray-700 mb-2">Caption Title (English)</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('judul_en') border-red-500 @enderror" 
                           id="judul_en" name="judul_en" value="{{ old('judul_en') }}" 
                           placeholder="Enter caption title (optional)">
                    @error('judul_en')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Caption -->
                <div>
                    <label for="jenis_konten" class="block text-sm font-medium text-gray-700 mb-2">Jenis Caption <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('jenis_konten') border-red-500 @enderror" 
                            id="jenis_konten" name="jenis_konten" onchange="toggleMediaFields()">
                        <option value="">Pilih Jenis Caption</option>
                        @foreach(App\Models\Content::getCaptionTypes() as $key => $value)
                            <option value="{{ $key }}" {{ old('jenis_konten') == $key ? 'selected' : '' }}>
                                {{ $value }}
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
                           id="published_at" name="published_at" value="{{ old('published_at') }}">
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
                        @foreach($beritas as $berita)
                            <option value="{{ $berita->id }}" {{ old('berita_id') == $berita->id ? 'selected' : '' }} {{ $selectedNews && $selectedNews->id == $berita->id ? 'selected' : '' }}>
                                {{ $berita->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('berita_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Desain Referensi (for Caption Media Kreatif / Caption Desain) -->
                <div class="col-span-2" id="desain_group" style="display: none;">
                    <label for="desain_id" class="block text-sm font-medium text-gray-700 mb-2">Desain Referensi <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('desain_id') border-red-500 @enderror" 
                            id="desain_id" name="desain_id">
                        <option value="">Pilih Desain</option>
                        @foreach($desains as $desain)
                            <option value="{{ $desain->id }}" {{ old('desain_id') == $desain->id ? 'selected' : '' }} {{ isset($selectedDesign) && $selectedDesign && $selectedDesign->id == $desain->id ? 'selected' : '' }}>
                                {{ $desain->judul }}
                            </option>
                        @endforeach
                    </select>
                    @error('desain_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Caption Content -->
                <div class="col-span-2" data-lang-section="id">
                    <label for="caption_id" class="block text-sm font-medium text-gray-700 mb-2">Caption (Indonesia) <span class="text-red-500">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('caption_id') border-red-500 @enderror" 
                              id="caption_id" name="caption_id" rows="6" 
                              placeholder="Tulis caption yang menarik dan informatif...">{{ old('caption_id') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Tulis caption yang menarik dan informatif. Maksimal 1000 karakter.</p>
                    @error('caption_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2 hidden" data-lang-section="en">
                    <label for="caption_en" class="block text-sm font-medium text-gray-700 mb-2">Caption (English) <span class="text-red-500">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('caption_en') border-red-500 @enderror" 
                              id="caption_en" name="caption_en" rows="6" 
                              placeholder="Write a compelling and informative caption...">{{ old('caption_en') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Write a compelling and informative caption. Max 1000 characters.</p>
                    @error('caption_en')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Platform Upload -->
                <div class="col-span-2">
                    <label for="platform_upload" class="block text-sm font-medium text-gray-700 mb-2">Platform Upload</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('platform_upload') border-red-500 @enderror" 
                            id="platform_upload" name="platform_upload">
                        <option value="">Pilih Platform Upload</option>
                        <option value="tiktok" {{ old('platform_upload') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                        <option value="instagram_feed" {{ old('platform_upload') == 'instagram_feed' ? 'selected' : '' }}>Instagram Feed</option>
                        <option value="instagram_story" {{ old('platform_upload') == 'instagram_story' ? 'selected' : '' }}>Instagram Story</option>
                        <option value="youtube" {{ old('platform_upload') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                    </select>
                    <p class="text-gray-500 text-xs mt-1">Pilih platform untuk mengupload caption ini.</p>
                    @error('platform_upload')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('koordinator-jurnalistik.contents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Caption
                </button>
            </div>
        </form>
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
    } else if (jenisKonten === 'caption_media_kreatif' || jenisKonten === 'caption_desain') {
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

    // Auto-select caption_media_kreatif and preselect desain if design is selected
    @if(isset($selectedDesign) && $selectedDesign)
        document.getElementById('jenis_konten').value = 'caption_media_kreatif';
        toggleMediaFields();
        const desainSelect = document.getElementById('desain_id');
        if (desainSelect) {
            desainSelect.value = '{{ $selectedDesign->id }}';
        }
    @endif
    
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
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection