<?php

namespace App\Services;

class ImageCropperService
{
    private const CROP_WIDTH = 1200;
    private const CROP_HEIGHT = 675;
    private const ASPECT_RATIO = 1.7777777777778; // 16:9

    public function getCropperStyles(): string
    {
        return '
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
            <style>
                .cropper-container {
                    margin: 20px auto;
                    max-width: 100%;
                }
                .preview-container {
                    overflow: hidden;
                    width: 100%;
                    height: 300px;
                }
                #cropModal {
                    display: none;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.9);
                }
                .modal-content {
                    background-color: #fefefe;
                    margin: 5% auto;
                    padding: 20px;
                    width: 90%;
                    max-width: 900px;
                    border-radius: 8px;
                }
            </style>
        ';
    }

    public function getCropperScripts(): string
    {
        return '
            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
            <script>
                let cropper;
                let tempImageId;

                function openCropModal(input) {
                    if (input.files && input.files[0]) {
                        const formData = new FormData();
                        formData.append("image", input.files[0]);
                        
                        // Upload original image first
                        fetch("/admin/temp-images", {
                            method: "POST",
                            body: formData,
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                tempImageId = data.image_id;
                                document.getElementById("cropImage").src = data.path;
                                document.getElementById("cropModal").style.display = "block";
                                initCropper();
                            }
                        })
                        .catch(error => {
                            console.error("Upload error:", error);
                            alert("Gagal mengunggah gambar. Silakan coba lagi.");
                        });
                    }
                }

                function initCropper() {
                    const image = document.getElementById("cropImage");
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(image, {
                        aspectRatio: ' . self::ASPECT_RATIO . ',
                        viewMode: 2,
                        dragMode: "move",
                        restore: false,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        minContainerWidth: 300,
                        minContainerHeight: 300,
                        ready: function() {
                            this.cropper.setCropBoxData({
                                width: ' . self::CROP_WIDTH . ',
                                height: ' . self::CROP_HEIGHT . '
                            });
                        }
                    });
                }

                function closeCropModal() {
                    document.getElementById("cropModal").style.display = "none";
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                }

                function cropImage() {
                    if (!cropper) return;

                    const canvas = cropper.getCroppedCanvas({
                        width: ' . self::CROP_WIDTH . ',
                        height: ' . self::CROP_HEIGHT . ',
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: "high"
                    });

                    // Tampilkan loading indicator
                    const loadingIndicator = document.createElement("div");
                    loadingIndicator.className = "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[1001]";
                    loadingIndicator.innerHTML = \'<div class="bg-white p-4 rounded-lg shadow-lg"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan gambar...</div>\';
                    document.body.appendChild(loadingIndicator);

                    // Kirim sebagai base64
                    fetch("/admin/temp-images/crop", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').content
                        },
                        body: JSON.stringify({
                            image: canvas.toDataURL("image/jpeg", 0.8),
                            temp_image_id: document.getElementById("temp_image_id").value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.remove();
                        if (data.status === "success") {
                            document.getElementById("preview").src = data.path;
                            closeCropModal();
                            
                            // Tampilkan notifikasi sukses
                            const notification = document.createElement("div");
                            notification.className = "fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50";
                            notification.innerHTML = \'<i class="fas fa-check-circle mr-2"></i>Gambar berhasil disimpan\';
                            document.body.appendChild(notification);
                            setTimeout(() => notification.remove(), 3000);
                        } else {
                            throw new Error(data.message || "Gagal menyimpan gambar");
                        }
                    })
                    .catch(error => {
                        loadingIndicator.remove();
                        console.error("Crop error:", error);
                        alert("Gagal menyimpan gambar yang sudah dipotong. Silakan coba lagi.");
                    });
                }
            </script>
        ';
    }
}