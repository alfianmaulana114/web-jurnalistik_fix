@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('header', 'Edit Berita')

@push('styles')
<style>
    .editor-container {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .custom-file-input:hover + .custom-file-label {
        background-color: #f3f4f6;
    }
    .preview-container {
        transition: all 0.3s ease;
    }
    .preview-container:hover {
        transform: scale(1.02);
    }
    .editor-btn {
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        transition: background-color 0.2s;
    }
    .editor-btn:hover {
        background-color: #f3f4f6;
    }
    #editor {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        min-height: 300px;
        padding: 1rem;
    }
    #editor:focus {
        outline: none;
        border-color: #a78bfa;
        box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.2);
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 rounded-xl shadow-lg p-8">
        <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="newsForm">
            @csrf
            @method('PUT')
            
            <!-- Title Input -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <label for="title" class="block text-lg font-semibold text-gray-800 mb-2">Judul Berita</label>
                <input type="text" 
                    name="title" 
                    id="title" 
                    class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                    value="{{ old('title', $news->title) }}" 
                    placeholder="Masukkan judul berita yang menarik"
                    required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori dan Sub Kategori -->
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-6">
                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-lg font-semibold text-gray-800 mb-2">Kategori</label>
                    <select name="category" id="category" class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Berita Nasional" {{ old('category', $news->category) == 'Berita Nasional' ? 'selected' : '' }}>Berita Nasional</option>
                        <option value="Berita Internasional" {{ old('category', $news->category) == 'Berita Internasional' ? 'selected' : '' }}>Berita Internasional</option>
                        <option value="Berita Internal" {{ old('category', $news->category) == 'Berita Internal' ? 'selected' : '' }}>Berita Internal</option>
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Sub Kategori -->
                <div>
                    <label for="subcategory" class="block text-lg font-semibold text-gray-800 mb-2">Sub Kategori</label>
                    <select name="subcategory" id="subcategory" class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200" required>
                        <option value="">Pilih Sub Kategori</option>
                        <option value="Berita Harian" {{ old('subcategory', $news->subcategory) == 'Berita Harian' ? 'selected' : '' }}>Berita Harian</option>
                        <option value="Berita Terkini" {{ old('subcategory', $news->subcategory) == 'Berita Terkini' ? 'selected' : '' }}>Berita Terkini</option>
                        <option value="Press Release" {{ old('subcategory', $news->subcategory) == 'Press Release' ? 'selected' : '' }}>Press Release</option>
                        <option value="Media Partner" {{ old('subcategory', $news->subcategory) == 'Media Partner' ? 'selected' : '' }}>Media Partner</option>
                    </select>
                    @error('subcategory')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Image Upload -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <label class="block text-lg font-semibold text-gray-800 mb-4">Gambar Utama</label>
                <div class="flex items-start space-x-6">
                    <div class="preview-container w-1/3">
                        <div class="relative aspect-video rounded-lg overflow-hidden bg-gray-50 border-2 border-dashed border-purple-200">
                            <img id="preview" 
                                src="{{ $news->image ? asset($news->image) : asset('/images/placeholder.jpg') }}" 
                                alt="Preview" 
                                class="w-full h-full object-cover transition duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition duration-300">
                                <button type="button" 
                                    onclick="document.getElementById('image').click()" 
                                    class="bg-white px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                                    <i class="fas fa-camera mr-2"></i>Ganti Gambar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                            <h4 class="font-medium text-gray-800 mb-2">Panduan Unggah Gambar:</h4>
                            <ul class="space-y-2">
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Format: JPG, PNG, GIF</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Ukuran maksimal: 2MB</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Rasio yang disarankan: 16:9</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @error('image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content Editor dengan tampilan yang lebih baik -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <label for="content" class="block text-lg font-semibold text-gray-800 mb-4">Konten Berita</label>
                <div class="editor-container">
                    <div class="bg-gray-100 p-2 border-b flex flex-wrap gap-2">
                        <button type="button" class="editor-btn" onclick="formatDoc('bold')"><i class="fas fa-bold"></i></button>
                        <button type="button" class="editor-btn" onclick="formatDoc('italic')"><i class="fas fa-italic"></i></button>
                        <button type="button" class="editor-btn" onclick="formatDoc('underline')"><i class="fas fa-underline"></i></button>
                        <button type="button" class="editor-btn" onclick="formatDoc('justifyLeft')"><i class="fas fa-align-left"></i></button>
                        <button type="button" class="editor-btn" onclick="formatDoc('justifyCenter')"><i class="fas fa-align-center"></i></button>
                        <button type="button" class="editor-btn" onclick="formatDoc('justifyRight')"><i class="fas fa-align-right"></i></button>
                        <button type="button" class="editor-btn" onclick="formatDoc('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
                        <button type="button" class="editor-btn" onclick="formatDoc('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
                    </div>
                    <div id="editor" contenteditable="true" class="p-4 min-h-[300px]">{!! old('content', $news->content) !!}</div>
                    <textarea name="content" id="content-field" class="hidden" required>{{ old('content', $news->content) }}</textarea>
                </div>
                @error('content')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('admin.news.index') }}" 
                    class="px-6 py-3 bg-white text-gray-700 rounded-lg border-2 border-gray-200 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Image Preview Function
    function previewImage(input) {
        const preview = document.getElementById('preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Simple WYSIWYG Editor Functions
    function formatDoc(command, value = null) {
        document.execCommand(command, false, value);
    }

    // Update hidden textarea with editor content before form submission
    document.getElementById('newsForm').addEventListener('submit', function(e) {
        const editorContent = document.getElementById('editor').innerHTML;
        document.getElementById('content-field').value = editorContent;
    });

    // Initialize preview on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check if the image exists
        const preview = document.getElementById('preview');
        const imagePath = preview.getAttribute('src');
        
        if (imagePath) {
            const img = new Image();
            img.onload = function() {
                // Image exists and loaded successfully
                preview.src = imagePath;
            };
            img.onerror = function() {
                // Image doesn't exist or failed to load
                preview.src = '/images/placeholder.jpg';
            };
            img.src = imagePath;
        }
    });
</script>
@endpush
@endsection