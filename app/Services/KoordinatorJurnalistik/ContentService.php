<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Content;
use App\Models\Brief;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentService
{
    public function index(Request $request): array
    {
        $query = Content::with(['brief', 'creator', 'berita', 'desain']);

        // Filter by search (judul atau caption)
        // Laravel's query builder automatically escapes LIKE parameters, but we validate input
        if ($request->has('search') && $request->search) {
            $search = trim($request->search);
            // Limit search length to prevent DoS
            if (strlen($search) > 255) {
                $search = substr($search, 0, 255);
            }
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('caption', 'like', '%' . $search . '%');
            });
        }

        // Filter by jenis_konten - validate against allowed types
        if ($request->has('jenis_konten') && $request->jenis_konten) {
            $allowedJenis = [
                Content::TYPE_CAPTION_BERITA,
                Content::TYPE_CAPTION_MEDIA_KREATIF,
                Content::TYPE_CAPTION_DESAIN
            ];
            if (in_array($request->jenis_konten, $allowedJenis)) {
                $query->where('jenis_konten', $request->jenis_konten);
            }
        }

        // Filter by media_type (berdasarkan berita atau desain) - validate input
        if ($request->has('media_type') && $request->media_type) {
            $allowedMediaTypes = ['image', 'video'];
            if (!in_array($request->media_type, $allowedMediaTypes)) {
                // Invalid media type, ignore filter
            } elseif ($request->media_type === 'image') {
                $query->where(function($q) {
                    $q->whereHas('berita', function($subQ) {
                        $subQ->whereNotNull('image');
                    })->orWhereHas('desain', function($subQ) {
                        $subQ->where('jenis', 'image');
                    });
                });
            } elseif ($request->media_type === 'video') {
                $query->where(function($q) {
                    $q->whereHas('berita', function($subQ) {
                        $subQ->whereNotNull('video_url');
                    })->orWhereHas('desain', function($subQ) {
                        $subQ->where('jenis', 'video');
                    });
                });
            }
        }

        $contents = $query->latest()->paginate(10)->withQueryString();
        
        // News untuk modal pilihan caption: kecualikan yang sudah punya caption berita
        $usedNewsIdsForCaptions = Content::where('jenis_konten', Content::TYPE_CAPTION_BERITA)
            ->whereNotNull('berita_id')
            ->pluck('berita_id')
            ->unique()
            ->toArray();
        $availableNews = \App\Models\News::with('category')
            ->whereNotIn('id', $usedNewsIdsForCaptions)
            ->latest()
            ->get();

        // Desain untuk modal pilihan caption berbasis desain: hanya desain tanpa berita terkait
        $availableDesigns = \App\Models\Design::query()
            ->whereNull('berita_id')
            ->latest()
            ->get();
        
        return compact('contents', 'availableNews', 'availableDesigns');
    }

    public function create(Request $request = null): array
    {
        $users = User::all();
        $beritas = \App\Models\News::all();
        // Ambil semua desain terbaru tanpa bergantung pada kolom status yang sudah dihapus
        $desains = \App\Models\Design::query()->latest()->get();
        
        $selectedNews = null;
        $selectedNewsTitle = null;
        $selectedDesign = null;
        $selectedDesignTitle = null;
        
        // Check if news_id parameter is provided
        if ($request && $request->has('news_id')) {
            $selectedNews = \App\Models\News::find($request->news_id);
            $selectedNewsTitle = $request->has('news_title') ? $request->news_title : ($selectedNews ? $selectedNews->title : null);
        }
        // Check if design_id parameter is provided
        if ($request && $request->has('design_id')) {
            $selectedDesign = \App\Models\Design::find($request->design_id);
            $selectedDesignTitle = $request->has('design_title') ? $request->design_title : ($selectedDesign ? $selectedDesign->judul : null);
        }
        
        return compact('users', 'beritas', 'desains', 'selectedNews', 'selectedNewsTitle', 'selectedDesign', 'selectedDesignTitle');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul_id' => 'nullable|string|max:255',
            'judul_en' => 'nullable|string|max:255',
            'caption_id' => 'required|string|max:1000',
            'caption_en' => 'required|string|max:1000',
            'jenis_konten' => ['required', Rule::in(array_keys(Content::getCaptionTypes()))],
            'brief_id' => 'nullable|exists:briefs,id',
            'published_at' => 'nullable|date',
            'platform_upload' => 'nullable|string',
            'berita_id' => 'nullable|exists:news,id',
            'desain_id' => 'nullable|exists:designs,id',
        ]);

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

        $validated['created_by'] = 1;

        $content = Content::create([
            'judul' => $validated['judul_id'] ?? null,
            'caption' => $validated['caption_id'],
            'jenis_konten' => $validated['jenis_konten'],
            'desain_id' => $validated['desain_id'] ?? null,
            'berita_id' => $validated['berita_id'] ?? null,
            'brief_id' => $validated['brief_id'] ?? null,
            'created_by' => $validated['created_by'],
            'published_at' => $validated['published_at'] ?? null,
            'platform_upload' => $validated['platform_upload'] ?? null,
        ]);

        $content->translations()->create([
            'locale' => 'id',
            'judul' => $validated['judul_id'] ?? null,
            'caption' => $validated['caption_id'],
        ]);

        $content->translations()->create([
            'locale' => 'en',
            'judul' => $validated['judul_en'] ?? null,
            'caption' => $validated['caption_en'],
        ]);

        if ($content->isCaptionBerita() && $content->berita_id) {
            $this->syncNewsMetaDescriptionFromCaption($content);
        }

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil ditambahkan.');
    }

    public function show(Content $content): array
    {
        // Muat relasi yang relevan dengan struktur baru
        $content->load(['brief', 'creator', 'desain.creator']);
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
            'judul_id' => 'nullable|string|max:255',
            'judul_en' => 'nullable|string|max:255',
            'caption_id' => 'required|string|max:1000',
            'caption_en' => 'required|string|max:1000',
            'jenis_konten' => ['required', Rule::in(array_keys(Content::getCaptionTypes()))],
            'berita_id' => 'nullable|exists:news,id',
            'desain_id' => 'nullable|exists:designs,id',
            'brief_id' => 'nullable|exists:briefs,id',
            'published_at' => 'nullable|date',
        ]);

        // Validasi referensi berdasarkan jenis caption
        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_BERITA && empty($validated['berita_id'] ?? null)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['berita_id' => 'Berita referensi wajib dipilih untuk caption berita']);
        }

        if ($validated['jenis_konten'] === Content::TYPE_CAPTION_MEDIA_KREATIF && empty($validated['desain_id'] ?? null)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['desain_id' => 'Desain referensi wajib dipilih untuk caption media kreatif']);
        }
        $content->update([
            'judul' => $validated['judul_id'] ?? $content->judul,
            'caption' => $validated['caption_id'],
            'jenis_konten' => $validated['jenis_konten'],
            'berita_id' => $validated['berita_id'] ?? $content->berita_id,
            'desain_id' => $validated['desain_id'] ?? $content->desain_id,
            'brief_id' => $validated['brief_id'] ?? $content->brief_id,
            'published_at' => $validated['published_at'] ?? $content->published_at,
        ]);

        $idTranslation = $content->translations()->where('locale', 'id')->first();
        if ($idTranslation) {
            $idTranslation->update([
                'judul' => $validated['judul_id'] ?? $idTranslation->judul,
                'caption' => $validated['caption_id'],
            ]);
        } else {
            $content->translations()->create([
                'locale' => 'id',
                'judul' => $validated['judul_id'] ?? null,
                'caption' => $validated['caption_id'],
            ]);
        }

        $enTranslation = $content->translations()->where('locale', 'en')->first();
        if ($enTranslation) {
            $enTranslation->update([
                'judul' => $validated['judul_en'] ?? $enTranslation->judul,
                'caption' => $validated['caption_en'],
            ]);
        } else {
            $content->translations()->create([
                'locale' => 'en',
                'judul' => $validated['judul_en'] ?? null,
                'caption' => $validated['caption_en'],
            ]);
        }

        if ($content->isCaptionBerita() && $content->berita_id) {
            $this->syncNewsMetaDescriptionFromCaption($content);
        }

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil diperbarui.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        $content->delete();

        return redirect()->route('koordinator-jurnalistik.contents.index')
            ->with('success', 'Caption berhasil dihapus.');
    }
    private function syncNewsMetaDescriptionFromCaption(Content $content): void
    {
        $news = \App\Models\News::find($content->berita_id);
        if (!$news) {
            return;
        }
        $idCaption = $content->translations()->where('locale', 'id')->value('caption') ?? $content->caption;
        $enCaption = $content->translations()->where('locale', 'en')->value('caption');
        $idMeta = Str::limit(trim(strip_tags($idCaption ?? '')), 160, '');
        $enMeta = Str::limit(trim(strip_tags($enCaption ?? '')), 160, '');
        $news->update([
            'meta_description' => $idMeta,
        ]);
        $idTrans = $news->translations()->where('locale', 'id')->first();
        if ($idTrans) {
            $idTrans->update(['meta_description' => $idMeta]);
        }
        $enTrans = $news->translations()->where('locale', 'en')->first();
        if ($enTrans) {
            $enTrans->update(['meta_description' => $enMeta]);
        }
    }
}


