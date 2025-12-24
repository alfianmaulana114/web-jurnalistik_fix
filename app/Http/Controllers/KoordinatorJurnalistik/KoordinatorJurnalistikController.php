<?php

namespace App\Http\Controllers\KoordinatorJurnalistik;

use App\Http\Controllers\Controller;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsType;
use App\Models\NewsGenre;
use App\Models\TempImage;
use App\Models\User;
use App\Models\Proker;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\NewsImageService;
use App\Services\EditorService;
use App\Services\ImageCropperService;
use Illuminate\Http\JsonResponse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class KoordinatorJurnalistikController extends Controller
{
    private $newsImageService;
    private $editorService;
    private $cropperService;
    private $manager;

    public function __construct(
        NewsImageService $imageService,
        EditorService $editorService,
        ImageCropperService $cropperService
    ) {
        $this->newsImageService = $imageService;
        $this->editorService = $editorService;
        $this->cropperService = $cropperService;
        $this->manager = new ImageManager(new Driver());
    }
    /**
     * Safe count method that handles missing tables
     */
    private function safeCount($modelClass)
    {
        try {
            return $modelClass::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safe query method that handles missing tables
     */
    private function safeQuery($callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Display the koordinator jurnalistik dashboard.
     * Dashboard lengkap mengenai semua divisi termasuk sekretaris dan bendahara
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\DashboardService::class)->getDashboardData();
        return view('koordinator-jurnalistik.dashboard', $data);
    }

    // News Management Methods
    /**
     * Menampilkan daftar semua berita untuk Koordinator Jurnalistik.
     *
     * @param Request $request
     * @return View
     */
    public function newsIndex(Request $request): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->index($request);
        return view('koordinator-jurnalistik.news.index', $data);
    }

    /**
     * Menampilkan form pembuatan berita baru.
     *
     * Memuat resource tambahan untuk editor teks dan cropper gambar.
     *
     * @return View
     */
    public function newsCreate(): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->create();
        return view('koordinator-jurnalistik.news.create', $data + [
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts(),
        ]);
    }

    /**
     * Menyimpan berita baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function newsStore(Request $request): RedirectResponse
    {
        return app(\App\Services\KoordinatorJurnalistik\NewsService::class)->store($request);
    }

    /**
     * Menampilkan detail berita.
     *
     * @param int $id
     * @return View
     */
    public function newsShow($id): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->show($id);
        return view('koordinator-jurnalistik.news.show', $data);
    }

    /**
     * Menampilkan form edit berita.
     *
     * Memuat resource editor dan cropper untuk pengeditan.
     *
     * @param int $id
     * @return View
     */
    public function newsEdit($id): View
    {
        $data = app(\App\Services\KoordinatorJurnalistik\NewsService::class)->edit($id);
        return view('koordinator-jurnalistik.news.edit', $data + [
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts(),
        ]);
    }

    /**
     * Memperbarui data berita.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function newsUpdate(Request $request, $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorJurnalistik\NewsService::class)->update($request, (int) $id);
    }

    /**
     * Menghapus berita.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function newsDestroy($id): RedirectResponse
    {
        return app(\App\Services\KoordinatorJurnalistik\NewsService::class)->destroy((int) $id);
    }

    /**
     * Mengunggah gambar sementara untuk konten berita.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $path = $this->newsImageService->store($request->file('image'));
            return response()->json(asset($path));
        }
        return response()->json('Upload failed', 400);
    }

    /**
     * Validasi input berita.
     *
     * @param Request $request
     * @param int|null $id
     * @return array
     */
    private function validateNews(Request $request, $id = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'required|string|max:160',
            'tags' => 'required|string',
            'keyword' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'news_type_id' => 'required|exists:news_types,id',
            'genre_ids' => 'required|array|exists:news_genres,id'
        ];
    
        return $request->validate($rules);
    }
}
