@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('header', 'Edit Berita')

@push('styles')
{!! $editorStyles !!}
{!! $cropperStyles !!}
<style>
    .preview-container {
        transition: all 0.3s ease;
    }
    .preview-container:hover {
        transform: scale(1.02);
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

            <!-- Kategori, Tipe, dan Genre -->
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-6">
                <!-- Kategori -->
                <div>
                    <label for="news_category_id" class="block text-lg font-semibold text-gray-800 mb-2">Kategori</label>
                    <select name="news_category_id" id="news_category_id" class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('news_category_id', $news->news_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('news_category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe -->
                <div>
                    <label for="news_type_id" class="block text-lg font-semibold text-gray-800 mb-2">Tipe Berita</label>
                    <select name="news_type_id" id="news_type_id" class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200" required>
                        <option value="">Pilih Tipe</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('news_type_id', $news->news_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('news_type_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Genre -->
                <div>
                    <label class="block text-lg font-semibold text-gray-800 mb-2">Genre Berita</label>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($genres as $genre)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="genre_ids[]" 
                                       value="{{ $genre->id }}"
                                       id="genre_{{ $genre->id }}"
                                       class="rounded border-gray-300 text-purple-600 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                       {{ in_array($genre->id, old('genre_ids', $news->genres->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label for="genre_{{ $genre->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $genre->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('genre_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Meta Description, Tags, dan Keyword -->
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-6">
                <div>
                    <label for="meta_description" class="block text-lg font-semibold text-gray-800 mb-2">Meta Description</label>
                    <textarea name="meta_description" 
                              id="meta_description" 
                              class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                              rows="3"
                              maxlength="160"
                              required>{{ old('meta_description', $news->meta_description) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 160 karakter</p>
                    @error('meta_description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tags" class="block text-lg font-semibold text-gray-800 mb-2">Tags</label>
                    <input type="text" 
                           name="tags" 
                           id="tags" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                           value="{{ old('tags', $news->tags) }}"
                           placeholder="Pisahkan dengan koma"
                           required>
                    @error('tags')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="keyword" class="block text-lg font-semibold text-gray-800 mb-2">Keyword</label>
                    <input type="text" 
                           name="keyword" 
                           id="keyword" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                           value="{{ old('keyword', $news->keyword) }}"
                           placeholder="Kata kunci untuk SEO">
                    @error('keyword')
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
                        <!-- Tambahkan input hidden untuk temp_image_id -->
                        <input type="hidden" name="temp_image_id" id="temp_image_id">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="openCropModal(this)">
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                            <h4 class="font-medium text-gray-800 mb-2">Panduan Unggah Gambar:</h4>
                            <ul class="space-y-2">
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Format: JPG, PNG, GIF</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Ukuran maksimal: 2MB</li>
                                <li><i class="fas fa-check-circle text-green-500 mr-2"></i>Rasio yang disarankan: 16:9 (1200x675 piksel)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @error('image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Crop Modal -->
            <div id="cropModal" class="modal">
                <div class="modal-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Sesuaikan Gambar</h3>
                        <button onclick="closeCropModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="preview-container mb-4">
                        <img id="cropImage" src="" alt="Crop Preview">
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button onclick="closeCropModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Batal
                        </button>
                        <button onclick="cropImage()" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                            Potong & Simpan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Editor -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <label for="content" class="block text-lg font-semibold text-gray-800 mb-4">Konten Berita</label>
                <textarea name="content" id="content" required>{{ old('content', $news->content) }}</textarea>
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
{!! $editorScripts !!}
{!! $cropperScripts !!}
<script>
    let cropper = null;
    
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const preview = document.getElementById('preview');
            
            reader.onload = function(e) {
                preview.style.opacity = '0';
                setTimeout(() => {
                    preview.src = e.target.result;
                    preview.style.opacity = '1';
                }, 200);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openCropModal(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const modal = document.getElementById('cropModal');
            const cropImage = document.getElementById('cropImage');
    
            reader.onload = function(e) {
                cropImage.src = e.target.result;
                modal.style.display = 'block';
    
                // Inisialisasi Cropper.js
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper(cropImage, {
                    aspectRatio: 16 / 9,
                    viewMode: 2,
                    minContainerWidth: 600,
                    minContainerHeight: 400,
                });
    
                // Upload gambar asli untuk mendapatkan temp_image_id
                const formData = new FormData();
                formData.append('image', input.files[0]);
    
                fetch('/admin/temp-images', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('temp_image_id').value = data.image_id;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengunggah gambar. Silakan coba lagi.');
                });
            };
    
            reader.readAsDataURL(input.files[0]);
        }
    }

    function closeCropModal() {
        const modal = document.getElementById('cropModal');
        modal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // Reset file input
        document.getElementById('image').value = '';
    }

    function cropImage() {
        if (!cropper) return;
    
        // Dapatkan data gambar yang sudah di-crop
        const canvas = cropper.getCroppedCanvas({
            width: 1200,
            height: 675
        });
    
        // Dapatkan temp_image_id
        const tempImageId = document.getElementById('temp_image_id').value;
        if (!tempImageId) {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            return;
        }
    
        // Kirim gambar yang sudah di-crop
        fetch('/admin/temp-images/crop', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                image: canvas.toDataURL('image/jpeg', 0.8),
                temp_image_id: tempImageId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('preview').src = data.path;
                closeCropModal();
            } else {
                alert(data.message || 'Gagal menyimpan gambar. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menyimpan gambar. Silakan coba lagi.');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const preview = document.getElementById('preview');
        const img = new Image();
        img.onload = function() {
            preview.src = preview.src;
        };
        img.src = preview.src;

        // Tambahkan style untuk modal
        const style = document.createElement('style');
        style.textContent = `
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
            }
            .modal-content {
                background-color: white;
                margin: 5% auto;
                padding: 20px;
                width: 80%;
                max-width: 800px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .preview-container img {
                max-width: 100%;
                max-height: 500px;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
@endsection