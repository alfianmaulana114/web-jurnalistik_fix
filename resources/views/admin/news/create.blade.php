@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Tambah Berita</h3>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Image Upload - Dipindah ke atas agar lebih menonjol -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                <div class="flex flex-col items-center p-6 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <img id="preview" src="{{ asset('images/placeholder.png') }}" alt="Preview" class="w-full max-w-lg h-auto mb-4 rounded-lg">
                    <input type="file" id="image" accept="image/*" class="hidden">
                    <input type="hidden" name="temp_image_id" id="temp_image_id">
                    <button type="button" onclick="document.getElementById('image').click()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-camera mr-2"></i>Pilih Gambar
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                    <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
                </div>

                <!-- Category & Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="news_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <select name="news_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
                        <option value="">Pilih Tipe</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Genre -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($genres as $genre)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="genre_ids[]" 
                                       value="{{ $genre->id }}"
                                       id="genre_{{ $genre->id }}"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
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

                <!-- Meta Description -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" required></textarea>
                </div>

                <!-- Tags & Keyword -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" name="tags" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keyword</label>
                    <input type="text" name="keyword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>

                <!-- Content -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konten</label>
                    <textarea name="content" id="content" required></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Berita
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Crop Modal -->
<div id="cropModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[10000] backdrop-blur-sm">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl p-6 w-[90%] max-w-3xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-medium text-gray-800">Sesuaikan Gambar</h3>
            <button type="button" class="closeModal text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="relative bg-gray-100 rounded-lg" style="height: 450px;">
            <img id="cropImage" src="" alt="Crop" class="max-w-full rounded-lg">
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" class="closeModal px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="button" id="cropBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-check mr-2"></i>Simpan
            </button>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .note-editor {
        border-radius: 0.375rem !important;
    }
    .note-toolbar {
        border-top-left-radius: 0.375rem !important;
        border-top-right-radius: 0.375rem !important;
        background-color: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    .note-statusbar {
        border-bottom-left-radius: 0.375rem !important;
        border-bottom-right-radius: 0.375rem !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
{!! $editorScripts !!}
{!! $cropperScripts !!}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper = null;
    const modal = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('preview');
    const tempImageIdInput = document.getElementById('temp_image_id');
    const cropBtn = document.getElementById('cropBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
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
                        zoomable: true
                    });
                };
            };

            reader.readAsDataURL(this.files[0]);
        }
    });

    document.querySelectorAll('.closeModal').forEach(button => {
        button.addEventListener('click', function() {
            modal.classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            fileInput.value = '';
        });
    });

    cropBtn.addEventListener('click', function() {
        if (!cropper) {
            alert('Silakan pilih gambar terlebih dahulu');
            return;
        }

        const loadingEl = document.createElement('div');
        loadingEl.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10002]';
        loadingEl.innerHTML = '<div class="bg-white p-4 rounded-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan gambar...</div>';
        document.body.appendChild(loadingEl);

        // Dapatkan canvas dari cropper
        const canvas = cropper.getCroppedCanvas({
            width: 1200,
            height: 675
        });

        // Konversi canvas ke blob
        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('image', blob, 'cropped.webp');
            formData.append('original_name', fileInput.files[0].name);
            formData.append('_token', csrfToken);

            fetch('/admin/temp-images', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json' // Tambahkan header ini
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Server returned ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                loadingEl.remove();
                if (data.status === 'success') {
                    tempImageIdInput.value = data.image_id;
                    preview.src = data.path;
                    modal.classList.add('hidden');
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    
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
                
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10002]';
                notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${error.message}`;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 5000);
                
                // Reset UI state
                modal.classList.add('hidden');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                fileInput.value = '';
            });
        }, 'image/webp', 0.8);
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