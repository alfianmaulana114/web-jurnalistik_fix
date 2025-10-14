@extends('layouts.koordinator-jurnalistik')

@section('title', 'Tambah Desain Media')
@section('header', 'Tambah Desain Media')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Tambah Desain Media Baru</h3>
                <a href="{{ route('koordinator-jurnalistik.designs.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </a>
            </div>
        </div>

        <form action="{{ route('koordinator-jurnalistik.designs.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Dasar</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="lg:col-span-2">
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Desain <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('judul') border-red-300 @enderror" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipe Desain <span class="text-red-500">*</span></label>
                        <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('type') border-red-300 @enderror" required>
                            <option value="">Pilih Tipe</option>
                            <option value="poster" {{ old('type') === 'poster' ? 'selected' : '' }}>Poster</option>
                            <option value="banner" {{ old('type') === 'banner' ? 'selected' : '' }}>Banner</option>
                            <option value="infographic" {{ old('type') === 'infographic' ? 'selected' : '' }}>Infografis</option>
                            <option value="logo" {{ old('type') === 'logo' ? 'selected' : '' }}>Logo</option>
                            <option value="flyer" {{ old('type') === 'flyer' ? 'selected' : '' }}>Flyer</option>
                            <option value="thumbnail" {{ old('type') === 'thumbnail' ? 'selected' : '' }}>Thumbnail</option>
                            <option value="social_media" {{ old('type') === 'social_media' ? 'selected' : '' }}>Media Sosial</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('status') border-red-300 @enderror" required>
                            <option value="">Pilih Status</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="review" {{ old('status') === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="needs_revision" {{ old('status') === 'needs_revision' ? 'selected' : '' }}>Perlu Revisi</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('deskripsi') border-red-300 @enderror" placeholder="Jelaskan konsep, tujuan, dan detail desain" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- File Upload -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">File Desain</h4>
                
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">Upload File Desain</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-red-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <div id="file-preview" class="hidden">
                                <img id="preview-image" class="mx-auto h-32 w-auto rounded-lg" src="" alt="Preview">
                            </div>
                            <div id="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                        <span>Upload file</span>
                                        <input id="file" name="file" type="file" class="sr-only" accept="image/*,.pdf,.ai,.psd,.eps">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, PDF, AI, PSD, EPS hingga 10MB</p>
                            </div>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="lebar" class="block text-sm font-medium text-gray-700">Lebar (px)</label>
                        <input type="number" name="lebar" id="lebar" value="{{ old('lebar') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('lebar') border-red-300 @enderror" min="1">
                        @error('lebar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tinggi" class="block text-sm font-medium text-gray-700">Tinggi (px)</label>
                        <input type="number" name="tinggi" id="tinggi" value="{{ old('tinggi') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('tinggi') border-red-300 @enderror" min="1">
                        @error('tinggi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ukuran_file" class="block text-sm font-medium text-gray-700">Ukuran File (KB)</label>
                        <input type="number" name="ukuran_file" id="ukuran_file" value="{{ old('ukuran_file') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('ukuran_file') border-red-300 @enderror" min="1" readonly>
                        @error('ukuran_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Relationships -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Keterkaitan</h4>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label for="content_id" class="block text-sm font-medium text-gray-700">Konten Terkait</label>
                        <select name="content_id" id="content_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('content_id') border-red-300 @enderror">
                            <option value="">Pilih Konten (Opsional)</option>
                            @foreach($contents ?? [] as $content)
                                <option value="{{ $content->id }}" {{ old('content_id') == $content->id ? 'selected' : '' }}>
                                    {{ $content->judul }} ({{ ucfirst($content->type) }})
                                </option>
                            @endforeach
                        </select>
                        @error('content_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="proker_id" class="block text-sm font-medium text-gray-700">Program Kerja Terkait</label>
                        <select name="proker_id" id="proker_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('proker_id') border-red-300 @enderror">
                            <option value="">Pilih Program Kerja (Opsional)</option>
                            @foreach($prokers ?? [] as $proker)
                                <option value="{{ $proker->id }}" {{ old('proker_id') == $proker->id ? 'selected' : '' }}>
                                    {{ $proker->nama }} ({{ ucfirst($proker->status) }})
                                </option>
                            @endforeach
                        </select>
                        @error('proker_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label for="creator_id" class="block text-sm font-medium text-gray-700">Pembuat Desain</label>
                        <select name="creator_id" id="creator_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('creator_id') border-red-300 @enderror">
                            <option value="">Pilih Pembuat (Opsional)</option>
                            @foreach($creators ?? [] as $creator)
                                <option value="{{ $creator->id }}" {{ old('creator_id') == $creator->id ? 'selected' : '' }}>
                                    {{ $creator->name }} ({{ $creator->role }})
                                </option>
                            @endforeach
                        </select>
                        @error('creator_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reviewer_id" class="block text-sm font-medium text-gray-700">Reviewer</label>
                        <select name="reviewer_id" id="reviewer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('reviewer_id') border-red-300 @enderror">
                            <option value="">Pilih Reviewer (Opsional)</option>
                            @foreach($reviewers ?? [] as $reviewer)
                                <option value="{{ $reviewer->id }}" {{ old('reviewer_id') == $reviewer->id ? 'selected' : '' }}>
                                    {{ $reviewer->name }} ({{ $reviewer->role }})
                                </option>
                            @endforeach
                        </select>
                        @error('reviewer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Catatan</h4>
                
                <div>
                    <label for="catatan_revisi" class="block text-sm font-medium text-gray-700">Catatan Revisi</label>
                    <textarea name="catatan_revisi" id="catatan_revisi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('catatan_revisi') border-red-300 @enderror" placeholder="Catatan untuk revisi atau feedback">{{ old('catatan_revisi') }}</textarea>
                    @error('catatan_revisi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Type Specific Guidelines -->
            <div id="typeGuidelines" class="space-y-4">
                <h4 class="text-md font-medium text-gray-900 border-b pb-2">Panduan Desain</h4>
                
                <!-- Poster Guidelines -->
                <div id="posterGuidelines" class="hidden bg-blue-50 border border-blue-200 rounded-md p-4">
                    <h5 class="text-sm font-medium text-blue-800 mb-2">Panduan Poster:</h5>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Ukuran standar: A3 (297x420mm) atau A4 (210x297mm)</li>
                        <li>• Resolusi minimal: 300 DPI untuk cetak</li>
                        <li>• Gunakan hierarki visual yang jelas</li>
                        <li>• Pastikan teks mudah dibaca dari jarak jauh</li>
                    </ul>
                </div>

                <!-- Banner Guidelines -->
                <div id="bannerGuidelines" class="hidden bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <h5 class="text-sm font-medium text-yellow-800 mb-2">Panduan Banner:</h5>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Ukuran web: 728x90px, 300x250px, atau 320x50px</li>
                        <li>• Ukuran cetak: sesuai kebutuhan lokasi</li>
                        <li>• Pesan harus singkat dan jelas</li>
                        <li>• Gunakan call-to-action yang menarik</li>
                    </ul>
                </div>

                <!-- Infographic Guidelines -->
                <div id="infographicGuidelines" class="hidden bg-green-50 border border-green-200 rounded-md p-4">
                    <h5 class="text-sm font-medium text-green-800 mb-2">Panduan Infografis:</h5>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li>• Gunakan data yang akurat dan terpercaya</li>
                        <li>• Buat alur informasi yang logis</li>
                        <li>• Gunakan ikon dan ilustrasi yang konsisten</li>
                        <li>• Pastikan kontras warna yang baik</li>
                    </ul>
                </div>

                <!-- Social Media Guidelines -->
                <div id="socialMediaGuidelines" class="hidden bg-purple-50 border border-purple-200 rounded-md p-4">
                    <h5 class="text-sm font-medium text-purple-800 mb-2">Panduan Media Sosial:</h5>
                    <ul class="text-sm text-purple-700 space-y-1">
                        <li>• Instagram Post: 1080x1080px (square)</li>
                        <li>• Instagram Story: 1080x1920px</li>
                        <li>• Facebook Post: 1200x630px</li>
                        <li>• Twitter Header: 1500x500px</li>
                    </ul>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('koordinator-jurnalistik.designs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Batal
                </a>
                <button type="submit" name="action" value="draft" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-save mr-2"></i>
                    Simpan sebagai Draft
                </button>
                <button type="submit" name="action" value="create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Desain
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const previewContainer = document.getElementById('file-preview');
    const previewImage = document.getElementById('preview-image');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const lebarInput = document.getElementById('lebar');
    const tinggiInput = document.getElementById('tinggi');
    const ukuranFileInput = document.getElementById('ukuran_file');
    const typeSelect = document.getElementById('type');

    // File upload handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show file size
            const fileSizeKB = Math.round(file.size / 1024);
            ukuranFileInput.value = fileSizeKB;

            // Show preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadPlaceholder.classList.add('hidden');

                    // Get image dimensions
                    const img = new Image();
                    img.onload = function() {
                        lebarInput.value = this.width;
                        tinggiInput.value = this.height;
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                // For non-image files, just hide placeholder
                uploadPlaceholder.classList.add('hidden');
                previewContainer.classList.add('hidden');
            }
        }
    });

    // Type-specific guidelines
    const guidelines = {
        poster: document.getElementById('posterGuidelines'),
        banner: document.getElementById('bannerGuidelines'),
        infographic: document.getElementById('infographicGuidelines'),
        social_media: document.getElementById('socialMediaGuidelines')
    };

    function showTypeGuidelines() {
        // Hide all guidelines first
        Object.values(guidelines).forEach(guideline => {
            if (guideline) guideline.classList.add('hidden');
        });

        // Show relevant guideline
        const selectedType = typeSelect.value;
        if (guidelines[selectedType]) {
            guidelines[selectedType].classList.remove('hidden');
        }
    }

    typeSelect.addEventListener('change', showTypeGuidelines);
    showTypeGuidelines(); // Initial check

    // Form submission handler
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const action = e.submitter.value;
        if (action === 'draft') {
            document.getElementById('status').value = 'draft';
        }
    });

    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
        
        // Initial resize
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-red-400', 'bg-red-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-red-400', 'bg-red-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    }

    // Relationship exclusivity (content OR proker, not both)
    const contentSelect = document.getElementById('content_id');
    const prokerSelect = document.getElementById('proker_id');

    contentSelect.addEventListener('change', function() {
        if (this.value) {
            prokerSelect.value = '';
        }
    });

    prokerSelect.addEventListener('change', function() {
        if (this.value) {
            contentSelect.value = '';
        }
    });
});
</script>
@endpush
@endsection