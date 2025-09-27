<?php

namespace App\Services;

class NewsContentService
{
    /**
     * Memproses konten berita sebelum disimpan
     */
    public static function processContent(string $content): string
    {
        return self::sanitizeContent($content);
    }

    /**
     * Membersihkan dan memformat konten
     */
    private static function sanitizeContent(string $content): string
    {
        $allowedTags = '<p><br><strong><em><ul><ol><li><img><a><h2><h3><blockquote>';
        $content = strip_tags($content, $allowedTags);
        
        // Format paragraf
        $content = preg_replace('/<p>/i', '<p class="mb-4 leading-relaxed text-gray-700">', $content);
        
        // Format heading
        $content = preg_replace('/<h2>/i', '<h2 class="text-2xl font-bold mb-3 mt-6">', $content);
        $content = preg_replace('/<h3>/i', '<h3 class="text-xl font-semibold mb-2 mt-4">', $content);
        
        // Format gambar
        $content = preg_replace('/<img/i', '<img class="max-w-full h-auto rounded-lg my-4" loading="lazy"', $content);
        
        return $content;
    }
}