@extends('layouts.admin')

// ... existing code ...

@push('scripts')
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

    // File input change handler
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                cropImage.src = e.target.result;
                modal.classList.remove('hidden');
                
                // Destroy existing cropper if any
                if (cropper) {
                    cropper.destroy();
                }
                
                // Initialize cropper after image loads
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
                        minContainerHeight: 400,
                        zoomable: true
                    });
                };

                // Upload original image
                const formData = new FormData();
                formData.append('image', fileInput.files[0]);
                formData.append('_token', csrfToken);

                fetch('/admin/temp-images', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        tempImageIdInput.value = data.image_id;
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

            reader.readAsDataURL(this.files[0]);
        }
    });

    // Close modal handlers
    document.querySelectorAll('.closeModal').forEach(button => {
        button.addEventListener('click', closeCropModal);
    });

    // Crop button handler
    cropBtn.addEventListener('click', function() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 1200,
            height: 675
        });

        const tempImageId = tempImageIdInput.value;
        if (!tempImageId) {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            return;
        }

        // Show loading indicator
        const loadingEl = document.createElement('div');
        loadingEl.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10002]';
        loadingEl.innerHTML = '<div class="bg-white p-4 rounded-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan gambar...</div>';
        document.body.appendChild(loadingEl);

        // Convert canvas to blob
        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('image', blob, 'cropped.jpg');
            formData.append('temp_image_id', tempImageId);
            formData.append('_token', csrfToken);

            fetch('/admin/temp-images/crop', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loadingEl.remove();
                if (data.status === 'success') {
                    preview.src = data.path;
                    closeCropModal();
                    
                    // Show success notification
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
        }, 'image/jpeg', 0.8);
    });

    function closeCropModal() {
        modal.classList.add('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }
});
</script>
@endpush
@endsection