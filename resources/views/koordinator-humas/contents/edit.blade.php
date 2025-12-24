@extends('layouts.koordinator-humas')

@section('title', 'Edit Content')
@section('header', 'Edit Content')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="rounded-xl border border-[#D8C4B6]/40 bg-white shadow-sm p-6">
        <h3 class="text-2xl font-medium text-[#1b334e] mb-6">Edit Content</h3>
        
        <form action="{{ route('koordinator-humas.contents.update', $content) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="button" class="px-4 py-2 text-sm font-medium border border-[#D8C4B6]/40 rounded-l-md bg-[#1b334e] text-white" data-lang-toggle="id">Indonesia</button>
                    <button type="button" class="px-4 py-2 text-sm font-medium border border-[#D8C4B6]/40 rounded-r-md bg-white text-gray-700" data-lang-toggle="en">English</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Caption -->
                <div class="col-span-2" data-lang-section="id">
                    <label for="judul_id" class="block text-sm font-medium text-gray-700 mb-2">Judul Caption (Indonesia)</label>
                    <input type="text" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] @error('judul_id') border-red-500 @enderror" 
                           id="judul_id" name="judul_id" value="{{ old('judul_id', $content->judul) }}" 
                           placeholder="Masukkan judul caption (opsional)">
                    @error('judul_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2 hidden" data-lang-section="en">
                    <label for="judul_en" class="block text-sm font-medium text-gray-700 mb-2">Caption Title (English)</label>
                    <input type="text" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] @error('judul_en') border-red-500 @enderror" 
                           id="judul_en" name="judul_en" value="{{ old('judul_en') }}" 
                           placeholder="Enter caption title (optional)">
                    @error('judul_en')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published At -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] @error('published_at') border-red-500 @enderror" 
                           id="published_at" name="published_at" 
                           value="{{ old('published_at', $content->published_at ? $content->published_at->format('Y-m-d\TH:i') : '') }}">
                    @error('published_at')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Platform Upload -->
                <div>
                    <label for="platform_upload" class="block text-sm font-medium text-gray-700 mb-2">Platform Upload</label>
                    <select class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] @error('platform_upload') border-red-500 @enderror" 
                            id="platform_upload" name="platform_upload">
                        <option value="">Pilih Platform Upload</option>
                        <option value="tiktok" {{ old('platform_upload', $content->platform_upload) == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                        <option value="instagram_feed" {{ old('platform_upload', $content->platform_upload) == 'instagram_feed' ? 'selected' : '' }}>Instagram Feed</option>
                        <option value="instagram_story" {{ old('platform_upload', $content->platform_upload) == 'instagram_story' ? 'selected' : '' }}>Instagram Story</option>
                        <option value="youtube" {{ old('platform_upload', $content->platform_upload) == 'youtube' ? 'selected' : '' }}>YouTube</option>
                    </select>
                    @error('platform_upload')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Desain Referensi -->
                <div class="col-span-2">
                    <label for="desain_id" class="block text-sm font-medium text-gray-700 mb-2">Desain Referensi <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] @error('desain_id') border-red-500 @enderror" 
                            id="desain_id" name="desain_id" required>
                        <option value="">Pilih Desain</option>
                        @foreach($desains as $desain)
                            <option value="{{ $desain->id }}" {{ old('desain_id', $content->desain_id) == $desain->id ? 'selected' : '' }}>
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
                    <textarea class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] @error('caption_id') border-red-500 @enderror" 
                              id="caption_id" name="caption_id" rows="6" 
                              placeholder="Tulis caption yang menarik dan informatif...">{{ old('caption_id', $content->caption) }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Tulis caption yang menarik dan informatif. Maksimal 1000 karakter.</p>
                    @error('caption_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-span-2 hidden" data-lang-section="en">
                    <label for="caption_en" class="block text-sm font-medium text-gray-700 mb-2">Caption (English) <span class="text-red-500">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-[#D8C4B6]/40 rounded-lg focus:ring-[#f9b61a] focus:border-[#f9b61a] @error('caption_en') border-red-500 @enderror" 
                              id="caption_en" name="caption_en" rows="6" 
                              placeholder="Write a compelling and informative caption...">{{ old('caption_en') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Write a compelling and informative caption. Max 1000 characters.</p>
                    @error('caption_en')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('koordinator-humas.contents.show', $content) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-[#1b334e] text-white rounded-lg hover:bg-[#1b334e]/90 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('[data-lang-toggle]');
    const sections = document.querySelectorAll('[data-lang-section]');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const lang = btn.getAttribute('data-lang-toggle');
            toggleButtons.forEach(b => {
                b.classList.remove('bg-[#1b334e]','text-white');
                b.classList.add('bg-white','text-gray-700');
            });
            btn.classList.add('bg-[#1b334e]','text-white');
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
@endsection

