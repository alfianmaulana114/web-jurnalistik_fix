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
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Caption <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror" 
                                       id="judul" name="judul" value="{{ old('judul', $content->judul) }}" 
                                       placeholder="Masukkan judul caption" required>
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

                            <!-- Brief Terkait -->
                            <div class="mb-4">
                                <label for="brief_id" class="block text-sm font-medium text-gray-700 mb-1">Brief Terkait</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('brief_id') border-red-500 @enderror" 
                                        id="brief_id" name="brief_id">
                                    <option value="">Pilih Brief (Opsional)</option>
                                    @foreach($briefs ?? [] as $brief)
                                        <option value="{{ $brief->id }}" {{ old('brief_id', $content->brief_id) == $brief->id ? 'selected' : '' }}>
                                            {{ $brief->judul }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brief_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Media Information (for Media Kreatif) -->
                        <div class="mb-6" id="mediaSection" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-photo-video text-blue-600 mr-2"></i>
                                Informasi Media
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="media_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Media <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('media_type') border-red-500 @enderror" 
                                            id="media_type" name="media_type">
                                        <option value="">Pilih Tipe Media</option>
                                        @foreach(App\Models\Content::getMediaTypes() as $key => $value)
                                            <option value="{{ $key }}" {{ old('media_type', $content->media_type) === $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('media_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="media_file" class="block text-sm font-medium text-gray-700 mb-1">File Media</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('media_file') border-red-500 @enderror" 
                                           id="media_file" name="media_file" accept="image/*,video/*">
                                    @if($content->media_path)
                                        <p class="text-gray-500 text-xs mt-1">
                                            File saat ini: <a href="{{ asset('storage/' . $content->media_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                                        </p>
                                    @endif
                                    @error('media_file')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="media_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Media</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('media_description') border-red-500 @enderror" 
                                          id="media_description" name="media_description" rows="3" 
                                          placeholder="Deskripsi singkat tentang media">{{ old('media_description', $content->media_description) }}</textarea>
                                @error('media_description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current Media Preview -->
                            @if($content->media_path)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Media Saat Ini:</label>
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        @if($content->isPhoto())
                                            <img src="{{ asset('storage/' . $content->media_path) }}" 
                                                 alt="Current Media" 
                                                 class="max-h-48 rounded-lg shadow-sm">
                                        @elseif($content->isVideo())
                                            <video controls class="max-h-48 rounded-lg shadow-sm">
                                                <source src="{{ asset('storage/' . $content->media_path) }}" type="video/mp4">
                                                Browser Anda tidak mendukung video.
                                            </video>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- News Reference (for News Captions) -->
                        <div class="mb-6" id="newsSection" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-newspaper text-blue-600 mr-2"></i>
                                Referensi Berita
                            </h3>
                            
                            <div class="mb-4">
                                <label for="berita_referensi" class="block text-sm font-medium text-gray-700 mb-1">Referensi Berita <span class="text-red-500">*</span></label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('berita_referensi') border-red-500 @enderror" 
                                          id="berita_referensi" name="berita_referensi" rows="4" 
                                          placeholder="Masukkan referensi berita yang akan dijadikan dasar caption">{{ old('berita_referensi', $content->berita_referensi) }}</textarea>
                                @error('berita_referensi')
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

                            <div class="mb-4">
                                <label for="sumber" class="block text-sm font-medium text-gray-700 mb-1">Sumber</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('sumber') border-red-500 @enderror" 
                                          id="sumber" name="sumber" rows="3" 
                                          placeholder="Sumber informasi atau referensi">{{ old('sumber', $content->sumber) }}</textarea>
                                @error('sumber')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Status & Publishing -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Publikasi</h3>
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                                        id="status" name="status" required>
                                    @foreach(App\Models\Content::getAllStatuses() as $key => $value)
                                        <option value="{{ $key }}" {{ old('status', $content->status) === $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="reviewed_by" class="block text-sm font-medium text-gray-700 mb-1">Reviewer</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('reviewed_by') border-red-500 @enderror" 
                                        id="reviewed_by" name="reviewed_by">
                                    <option value="">Pilih Reviewer (Opsional)</option>
                                    @foreach($reviewers ?? [] as $reviewer)
                                        <option value="{{ $reviewer->id }}" {{ old('reviewed_by', $content->reviewed_by) == $reviewer->id ? 'selected' : '' }}>
                                            {{ $reviewer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reviewed_by')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4" id="publishDateSection" style="display: none;">
                                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publikasi</label>
                                <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('published_at') border-red-500 @enderror" 
                                       id="published_at" name="published_at" 
                                       value="{{ old('published_at', $content->published_at ? $content->published_at->format('Y-m-d\TH:i') : '') }}">
                                @error('published_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Editor Notes -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan Editor</h3>
                            
                            <div class="mb-4">
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('catatan_editor') border-red-500 @enderror" 
                                          id="catatan_editor" name="catatan_editor" rows="4" 
                                          placeholder="Catatan untuk proses editorial">{{ old('catatan_editor', $content->catatan_editor) }}</textarea>
                                @error('catatan_editor')
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
    const mediaSection = document.getElementById('mediaSection');
    const newsSection = document.getElementById('newsSection');
    const mediaTypeSelect = document.getElementById('media_type');
    const beritaReferensiField = document.getElementById('berita_referensi');
    const statusSelect = document.getElementById('status');
    const publishDateSection = document.getElementById('publishDateSection');

    // Handle jenis konten change
    function handleJenisKontenChange() {
        const selectedValue = jenisKontenSelect.value;
        
        if (selectedValue === 'caption_berita') {
            newsSection.style.display = 'block';
            mediaSection.style.display = 'none';
            beritaReferensiField.required = true;
            mediaTypeSelect.required = false;
        } else if (selectedValue === 'caption_media_kreatif') {
            mediaSection.style.display = 'block';
            newsSection.style.display = 'none';
            mediaTypeSelect.required = true;
            beritaReferensiField.required = false;
        } else {
            mediaSection.style.display = 'none';
            newsSection.style.display = 'none';
            mediaTypeSelect.required = false;
            beritaReferensiField.required = false;
        }
    }

    // Handle status change
    function handleStatusChange() {
        if (statusSelect.value === 'published') {
            publishDateSection.style.display = 'block';
            document.getElementById('published_at').required = true;
        } else {
            publishDateSection.style.display = 'none';
            document.getElementById('published_at').required = false;
        }
    }

    // Event listeners
    jenisKontenSelect.addEventListener('change', handleJenisKontenChange);
    statusSelect.addEventListener('change', handleStatusChange);

    // Initial setup
    handleJenisKontenChange();
    handleStatusChange();

    // File input preview
    const mediaFileInput = document.getElementById('media_file');
    if (mediaFileInput) {
        mediaFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add preview functionality here if needed
                    console.log('File selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
});
</script>
@endpush
@endsection