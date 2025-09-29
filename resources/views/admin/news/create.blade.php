@extends('layouts.admin')

@section('title', 'Tambah Berita Baru')

@section('header', 'Tambah Berita Baru')

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
    .cropper-container {
        z-index: 10001 !important;
    }
    .cropper-modal {
        z-index: 10000 !important;
    }
    .cropper-view-box {
        outline: 2px solid #fff !important;
        outline-color: rgba(255, 255, 255, 0.75) !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 rounded-xl shadow-lg p-8">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="newsForm">
            @csrf
            
            <!-- Title Input -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <label for="title" class="block text-lg font-semibold text-gray-800 mb-2">Judul Berita</label>
                <input type="text" 
                    name="title" 
                    id="title" 
                    class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200"
                    value="{{ old('title') }}" 
                    placeholder="Masukkan judul berita yang menarik"
                    required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori, Tipe, dan Genre -->
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-6">
                <!-- Kategori -->
                <!-- Kategori -->
                <div>
                    <label for="news_category_id" class="block text-lg font-semibold text-gray-800 mb-2">Kategori</label>
                    <select name="news_category_id" id="news_category_id" class="w-full px-4 py-3 rounded-lg border-2 border-purple-100 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition duration-200" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('news_category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $type->id }}" {{ old('news_type_id') == $type->id ? 'selected' : '' }}>
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
                                       {{ in_array($genre->id, old('genre_ids', [])) ? 'checked' : '' }}>
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
                              required>{{ old('meta_description') }}</textarea>
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
                           value="{{ old('tags') }}"
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
                           value="{{ old('keyword') }}"
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
                            <img id="preview" src="{{ asset('images/placeholder.png') }}" alt="Preview" 
                                class="w-full h-full object-cover transition duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition duration-300">
                                <button type="button" 
                                    onclick="document.getElementById('image').click()" 
                                    class="bg-white px-4 py-2 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                                    <i class="fas fa-camera mr-2"></i>Pilih Gambar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <!-- Di dalam form, tambahkan input hidden untuk temp_image_id -->
                        <input type="hidden" name="temp_image_id" id="temp_image_id">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="openCropModal(this)" required>
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
            <!-- Crop Modal -->
            <div id="cropModal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden">
                <div class="bg-white rounded-lg shadow-xl max-w-4xl mx-auto mt-10 p-6 relative z-[10000]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Potong Gambar</h3>
                        <button type="button" class="text-gray-500 hover:text-gray-700 closeModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="preview-container mb-4">
                        <img id="cropImage" src="" alt="Preview" class="max-w-full">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 closeModal">
                            Batal
                        </button>
                        <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" id="cropBtn">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
            <!-- Hidden Input for Temp Image ID -->
            <input type="hidden" name="temp_image_id" id="temp_image_id">
            <!-- Content Editor -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <label for="content" class="block text-lg font-semibold text-gray-800 mb-4">Konten Berita</label>
                <textarea name="content" id="content" required>{{ old('content') }}</textarea>
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
                    <i class="fas fa-save mr-2"></i>Simpan Berita
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

document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk tombol file input
    document.getElementById('image').addEventListener('change', function(e) {
        openCropModal(this);
    });

    // Event listeners untuk tombol-tombol modal
    document.querySelectorAll('.closeModal').forEach(button => {
        button.addEventListener('click', closeCropModal);
    });

    document.getElementById('cropBtn').addEventListener('click', cropImage);

    function openCropModal(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const modal = document.getElementById('cropModal');
            const cropImage = document.getElementById('cropImage');

            reader.onload = function(e) {
                cropImage.src = e.target.result;
                modal.classList.remove('hidden');

                if (cropper) {
                    cropper.destroy();
                }

                // Tunggu gambar dimuat sebelum inisialisasi cropper
                cropImage.onload = function() {
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 16 / 9,
                        viewMode: 2,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        minContainerWidth: 600,
                        minContainerHeight: 400
                    });
                };

                // Upload gambar asli
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
                    } else {
                        throw new Error(data.message || 'Gagal mengunggah gambar');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengunggah gambar. Silakan coba lagi.');
                    closeCropModal();
                });
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function closeCropModal() {
        const modal = document.getElementById('cropModal');
        modal.classList.add('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    function cropImage() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 1200,
            height: 675
        });

        const tempImageId = document.getElementById('temp_image_id').value;
        if (!tempImageId) {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            return;
        }

        // Tampilkan loading
        const loadingEl = document.createElement('div');
        loadingEl.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10002]';
        loadingEl.innerHTML = '<div class="bg-white p-4 rounded-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan gambar...</div>';
        document.body.appendChild(loadingEl);

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
            loadingEl.remove();
            if (data.status === 'success') {
                document.getElementById('preview').src = data.path;
                closeCropModal();
                
                // Tampilkan notifikasi sukses
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10002]';
                notification.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Gambar berhasil disimpan';
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
            } else {
                throw new Error(data.message || 'Gagal menyimpan gambar');
            }
        })
        .catch(error => {
            loadingEl.remove();
            console.error('Error:', error);
            alert('Gagal menyimpan gambar. Silakan coba lagi.');
        });
    }
});
</script>
@endpush
@endsection