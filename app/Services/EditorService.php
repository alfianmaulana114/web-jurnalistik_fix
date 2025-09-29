<?php

namespace App\Services;

class EditorService
{
    /**
     * Get the required CSS for Summernote editor
     *
     * @return string
     */
    public function getEditorStyles(): string
    {
        return '
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
            <style>
                .note-editor {
                    background: white;
                }
                .note-editor.note-frame {
                    border: 2px solid #e5e7eb !important;
                    border-radius: 0.5rem;
                }
                .note-editor.note-frame:focus-within {
                    border-color: #a78bfa !important;
                    box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.2);
                }
                .note-toolbar {
                    background: #f3f4f6 !important;
                    border-bottom: 1px solid #e5e7eb !important;
                    border-radius: 0.5rem 0.5rem 0 0;
                }
                .note-btn {
                    background: white !important;
                    border-color: #e5e7eb !important;
                }
                .note-btn:hover {
                    background: #f9fafb !important;
                    border-color: #d1d5db !important;
                }
            </style>
        ';
    }

    /**
     * Get the required JavaScript for Summernote editor
     *
     * @return string
     */
    public function getEditorScripts(): string
    {
        return '
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
            <script>
                $(document).ready(function() {
                    $("#content").summernote({
                        placeholder: "Tulis konten berita di sini...",
                        tabsize: 2,
                        height: 300,
                        toolbar: [
                            ["style", ["style"]],
                            ["font", ["bold", "underline", "italic", "clear"]],
                            ["color", ["color"]],
                            ["para", ["ul", "ol", "paragraph"]],
                            ["table", ["table"]],
                            ["insert", ["link", "picture"]],
                            ["view", ["fullscreen", "codeview", "help"]]
                        ],
                        callbacks: {
                            onImageUpload: function(files) {
                                for(let i=0; i < files.length; i++) {
                                    uploadImage(files[i]);
                                }
                            }
                        }
                    });
                });

                function uploadImage(file) {
                    let form = new FormData();
                    form.append("image", file);
                    
                    $.ajax({
                        url: "/admin/upload-image",
                        method: "POST",
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $("meta[name=\'csrf-token\']").attr("content")
                        },
                        success: function(url) {
                            $("#content").summernote("insertImage", url);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Upload failed:", errorThrown);
                            alert("Gagal mengunggah gambar. Silakan coba lagi.");
                        }
                    });
                }
            </script>
        ';
    }
}