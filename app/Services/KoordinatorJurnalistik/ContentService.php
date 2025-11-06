<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Content;
use App\Models\Brief;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ContentService
{
    public function index(): array
    {
        $contents = Content::with(['brief', 'creator', 'designs', 'berita', 'desain'])
            ->latest()
            ->paginate(10);
        
        // Get all news for selection (since there's no direct relationship via foreign key)
        $availableNews = \App\Models\News::with('category')
            ->latest()
            ->get();
            
        return compact('contents', 'availableNews');
    }

    public function create(Request $request = null): array
    {
        $users = User::all();
        $beritas = \App\Models\News::all();
        $desains = \App\Models\Design::approved()->get();
        
        $selectedNews = null;
        $selectedNewsTitle = null;
        
        // Check if news_id parameter is provided
        if ($request && $request->has('news_id')) {
            $selectedNews = \App\Models\News::find($request->news_id);
            $selectedNewsTitle = $request->has('news_title') ? $request->news_title : ($selectedNews ? $selectedNews->title : null);
        }
        
        return compact('users', 'beritas', 'desains', 'selectedNews', 'selectedNewsTitle');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'caption' => 'required|string|max:1000',
            'jenis_konten' => ['required', Rule::in(array_keys(Content::getCaptionTypes()))],
            'media_type' => ['nullable', Rule::in(array_keys(Content::getMediaTypes()))],
            'media_file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480',
            'media_description' => 'nullable|string|max:500',
            'berita_referensi' => 'nullable|string',
            'sumber' => 'nullable|string',
            'catatan_editor' => 'nullable|string',
            'brief_id' => 'nullable|exists:briefs,id',
            'published_at' => 'nullable|date',
            // Tambahkan relasi yang benar
            'berita_id' => 'nullable|exists:news,id',
            'desain_id' => 'nullable|exists:designs,id',
        ]);

        // Handle media file upload
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('captions/media', $fileName, 'public');
            $validated['media_path'] = $filePath;
        }

        // Set media_type based on jenis_konten if not provided
        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_MEDIA_KREATIF && !$validated['media_type']) {
            $validated['media_type'] = Content::MEDIA_TYPE_FOTO; // default
        }

        // Validasi referensi berdasarkan jenis caption saat create
        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_BERITA && empty($validated['berita_id'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['berita_id' => 'Berita referensi wajib dipilih untuk caption berita']);
        }
        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_MEDIA_KREATIF && empty($validated['desain_id'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['desain_id' => 'Desain referensi wajib dipilih untuk caption media kreatif']);
        }

        // Assume current user is koordinator jurnalistik (ID 1 for now)
        $validated['created_by'] = 1;

        Content::create($validated);

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil ditambahkan.');
    }

    public function show(Content $content): array
    {
        $content->load(['brief', 'creator', 'designs.creator']);
        return compact('content');
    }

    public function edit(Content $content): array
    {
        $briefs = Brief::active()->get();
        $users = User::all();
        return compact('content', 'briefs', 'users');
    }

    public function update(Request $request, Content $content): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'caption' => 'required|string|max:1000',
            'jenis_konten' => ['required', Rule::in(array_keys(Content::getCaptionTypes()))],
            'berita_id' => 'nullable|exists:news,id',
            'desain_id' => 'nullable|exists:designs,id',
            'brief_id' => 'nullable|exists:briefs,id',
            'published_at' => 'nullable|date',
        ]);

        // Validasi referensi berdasarkan jenis caption
        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_BERITA && !$validated['berita_id']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['berita_id' => 'Berita referensi wajib dipilih untuk caption berita']);
        }

        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_MEDIA_KREATIF && !$validated['desain_id']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['desain_id' => 'Desain referensi wajib dipilih untuk caption media kreatif']);
        }



        $content->update($validated);

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil diperbarui.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        // Delete associated media file
        if ($content->media_path && Storage::disk('public')->exists($content->media_path)) {
            Storage::disk('public')->delete($content->media_path);
        }

        $content->delete();

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil dihapus.');
    }


}


