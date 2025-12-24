<?php

namespace App\Services\KoordinatorHumas;

use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class ContentService
{
    public function index(): array
    {
        $contents = Content::where('jenis_konten', Content::TYPE_CAPTION_MEDIA_KREATIF)
            ->with(['brief', 'creator', 'berita', 'desain'])
            ->latest()
            ->paginate(10);
        
        // Desain untuk modal pilihan caption berbasis desain
        $availableDesigns = \App\Models\Design::query()
            ->whereNull('berita_id')
            ->latest()
            ->get();
        
        return compact('contents', 'availableDesigns');
    }

    public function create(Request $request = null): array
    {
        $users = User::all();
        $desains = \App\Models\Design::query()->latest()->get();
        
        $selectedDesign = null;
        $selectedDesignTitle = null;
        
        // Check if design_id parameter is provided
        if ($request && $request->has('design_id')) {
            $selectedDesign = \App\Models\Design::find($request->design_id);
            $selectedDesignTitle = $request->has('design_title') ? $request->design_title : ($selectedDesign ? $selectedDesign->judul : null);
        }
        
        return compact('users', 'desains', 'selectedDesign', 'selectedDesignTitle');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul_id' => 'nullable|string|max:255',
            'judul_en' => 'nullable|string|max:255',
            'caption_id' => 'required|string|max:1000',
            'caption_en' => 'required|string|max:1000',
            'desain_id' => 'nullable|exists:designs,id',
            'published_at' => 'nullable|date',
            'platform_upload' => 'nullable|string',
        ]);

        // Set jenis konten untuk humas
        $validated['jenis_konten'] = Content::TYPE_CAPTION_MEDIA_KREATIF;
        $validated['created_by'] = auth()->id();

        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_MEDIA_KREATIF && empty($validated['desain_id'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['desain_id' => 'Desain referensi wajib dipilih untuk caption media kreatif']);
        }

        Content::create([
            'judul' => $validated['judul_id'] ?? null,
            'caption' => $validated['caption_id'],
            'jenis_konten' => $validated['jenis_konten'],
            'desain_id' => $validated['desain_id'] ?? null,
            'created_by' => $validated['created_by'],
            'published_at' => $validated['published_at'] ?? null,
            'platform_upload' => $validated['platform_upload'] ?? null,
        ]);

        return redirect()->route('koordinator-humas.contents.index')
            ->with('success', 'Content berhasil ditambahkan.');
    }

    public function show(Content $content): array
    {
        $content->load(['brief', 'creator', 'berita', 'desain']);
        return compact('content');
    }

    public function edit(Content $content): array
    {
        $users = User::all();
        $desains = \App\Models\Design::query()->latest()->get();
        return compact('content', 'users', 'desains');
    }

    public function update(Request $request, Content $content): RedirectResponse
    {
        $validated = $request->validate([
            'judul_id' => 'nullable|string|max:255',
            'judul_en' => 'nullable|string|max:255',
            'caption_id' => 'required|string|max:1000',
            'caption_en' => 'required|string|max:1000',
            'desain_id' => 'nullable|exists:designs,id',
            'published_at' => 'nullable|date',
            'platform_upload' => 'nullable|string',
        ]);

        $content->update([
            'judul' => $validated['judul_id'] ?? null,
            'caption' => $validated['caption_id'],
            'desain_id' => $validated['desain_id'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'platform_upload' => $validated['platform_upload'] ?? null,
        ]);

        return redirect()->route('koordinator-humas.contents.index')
            ->with('success', 'Content berhasil diperbarui.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        $content->delete();
        return redirect()->route('koordinator-humas.contents.index')
            ->with('success', 'Content berhasil dihapus.');
    }
}

