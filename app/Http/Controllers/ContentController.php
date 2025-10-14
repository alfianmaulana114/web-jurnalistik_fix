<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Brief;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of captions.
     */
    public function index(): View
    {
        $contents = Content::with(['brief', 'creator', 'reviewer', 'designs'])
            ->latest()
            ->paginate(10);
            
        return view('koordinator-jurnalistik.contents.index', compact('contents'));
    }

    /**
     * Show the form for creating a new caption.
     */
    public function create(): View
    {
        $briefs = Brief::active()->get();
        $users = User::all();
        return view('koordinator-jurnalistik.contents.create', compact('briefs', 'users'));
    }

    /**
     * Store a newly created caption in storage.
     */
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
            'status' => ['required', Rule::in(array_keys(Content::getAllStatuses()))],
            'brief_id' => 'nullable|exists:briefs,id',
            'reviewed_by' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
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

        // Assume current user is koordinator jurnalistik (ID 1 for now)
        $validated['created_by'] = 1;

        Content::create($validated);

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil ditambahkan.');
    }

    /**
     * Display the specified caption.
     */
    public function show(Content $content): View
    {
        $content->load(['brief', 'creator', 'reviewer', 'designs.creator']);
        return view('koordinator-jurnalistik.contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified caption.
     */
    public function edit(Content $content): View
    {
        $briefs = Brief::active()->get();
        $users = User::all();
        return view('koordinator-jurnalistik.contents.edit', compact('content', 'briefs', 'users'));
    }

    /**
     * Update the specified caption in storage.
     */
    public function update(Request $request, Content $content): RedirectResponse
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
            'status' => ['required', Rule::in(array_keys(Content::getAllStatuses()))],
            'brief_id' => 'nullable|exists:briefs,id',
            'reviewed_by' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
        ]);

        // Handle media file upload
        if ($request->hasFile('media_file')) {
            // Delete old file if exists
            if ($content->media_path && Storage::disk('public')->exists($content->media_path)) {
                Storage::disk('public')->delete($content->media_path);
            }
            
            $file = $request->file('media_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('captions/media', $fileName, 'public');
            $validated['media_path'] = $filePath;
        }

        // Set media_type based on jenis_konten if not provided
        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_MEDIA_KREATIF && !$validated['media_type']) {
            $validated['media_type'] = Content::MEDIA_TYPE_FOTO; // default
        }

        $content->update($validated);

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil diperbarui.');
    }

    /**
     * Remove the specified caption from storage.
     */
    public function destroy(Content $content): RedirectResponse
    {
        // Delete associated media file if exists
        if ($content->media_path && Storage::disk('public')->exists($content->media_path)) {
            Storage::disk('public')->delete($content->media_path);
        }
        
        $content->delete();

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil dihapus.');
    }

    /**
     * Update caption status (for AJAX requests)
     */
    public function updateStatus(Request $request, Content $content)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Content::getAllStatuses()))],
        ]);

        $content->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status caption berhasil diperbarui.',
            'status' => $content->getStatusLabel()
        ]);
    }
}