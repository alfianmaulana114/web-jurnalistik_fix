@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-2xl font-medium text-gray-800 mb-6">Edit Berita</h3>

        <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Image Upload -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                <div class="flex flex-col items-center p-6 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                    <img id="preview" src="{{ $news->image ? asset($news->image) : asset('images/placeholder.png') }}" alt="Preview" class="w-full max-w-lg h-auto mb-4 rounded-lg">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Berita</label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title', $news->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" 
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="news_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <select name="news_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent" required>
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
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($genres as $genre)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="genre_ids[]" 
                                       value="{{ $genre->id }}"
                                       id="genre_{{ $genre->id }}"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-400"
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

                <!-- Meta Description -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                              maxlength="160"
                              required>{{ old('meta_description', $news->meta_description) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 160 karakter</p>
                    @error('meta_description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" 
                           name="tags" 
                           value="{{ old('tags', $news->tags) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           placeholder="Pisahkan dengan koma"
                           required>
                    @error('tags')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keyword -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keyword</label>
                    <input type="text" 
                           name="keyword" 
                           value="{{ old('keyword', $news->keyword) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                           placeholder="Kata kunci untuk SEO">
                    @error('keyword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konten Berita</label>
                    <textarea name="content" 
                              id="content"
                              required>{{ old('content', $news->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.news.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Crop Modal -->
<div id="cropModal" class="fixed inset-0 bg-black bg-opacity-50 z-[10001] hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl mx-auto mt-20 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Sesuaikan Gambar</h3>
            <button class="closeModal text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mb-4">
            <img id="cropImage" src="" alt="Crop Preview">
        </div>
        <div class="flex justify-end space-x-3">
            <button class="closeModal px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Batal
            </button>
            <button id="cropBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Potong & Simpan
            </button>
        </div>
    </div>
</div>

@push('styles')
{!! $editorStyles !!}
{!! $cropperStyles !!}
@endpush

@push('scripts')
{!! $editorScripts !!}
{!! $cropperScripts !!}
<script>
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

        const canvas = cropper.getCroppedCanvas({
            width: 1200,
            height: 675
        });

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('image', blob, 'cropped.webp');
            formData.append('original_name', fileInput.files[0].name);
            formData.append('_token', csrfToken);

            fetch('/admin/temp-images', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    preview.src = data.path;
                    tempImageIdInput.value = data.image_id;
                    modal.classList.add('hidden');
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    fileInput.value = '';
                } else {
                    alert(data.message || 'Gagal menyimpan gambar');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menyimpan gambar');
            })
            .finally(() => {
                document.body.removeChild(loadingEl);
            });
        }, 'image/webp', 0.8);
    });
</script>
@endpush

@endsection