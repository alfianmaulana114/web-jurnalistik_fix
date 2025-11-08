<?php

namespace App\Http\Controllers\KoordinatorRedaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\EditorService;
use App\Services\ImageCropperService;

/**
 * Controller untuk Koordinator Redaksi
 * 
 * Controller ini menangani semua request yang terkait dengan koordinator redaksi,
 * termasuk dashboard dan manajemen berita. Mengikuti prinsip Single Responsibility
 * dengan delegasi logika bisnis ke Service layer.
 */
class KoordinatorRedaksiController extends Controller
{
    /**
     * Service untuk editor (Summernote)
     * 
     * @var EditorService
     */
    private EditorService $editorService;

    /**
     * Service untuk image cropper (Cropper.js)
     * 
     * @var ImageCropperService
     */
    private ImageCropperService $cropperService;

    /**
     * Constructor untuk dependency injection
     * 
     * @param EditorService $editorService
     * @param ImageCropperService $cropperService
     */
    public function __construct(
        EditorService $editorService,
        ImageCropperService $cropperService
    ) {
        $this->editorService = $editorService;
        $this->cropperService = $cropperService;
    }

    /**
     * Menampilkan dashboard koordinator redaksi
     * 
     * Dashboard menampilkan informasi lengkap tentang divisi redaksi,
     * termasuk brief dari litbang, caption, dan design.
     * 
     * @return View
     */
    public function dashboard(): View
    {
        $data = app(\App\Services\KoordinatorRedaksi\DashboardService::class)->getDashboardData();
        return view('koordinator-redaksi.dashboard', $data);
    }

    /**
     * Menampilkan daftar semua berita
     * 
     * @return View
     */
    public function newsIndex(): View
    {
        $data = app(\App\Services\KoordinatorRedaksi\NewsService::class)->index();
        return view('koordinator-redaksi.news.index', $data);
    }

    /**
     * Menampilkan form untuk membuat berita baru
     * 
     * @return View
     */
    public function newsCreate(): View
    {
        $data = app(\App\Services\KoordinatorRedaksi\NewsService::class)->create();
        return view('koordinator-redaksi.news.create', $data + [
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts(),
        ]);
    }

    /**
     * Menyimpan berita baru
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function newsStore(Request $request): RedirectResponse
    {
        return app(\App\Services\KoordinatorRedaksi\NewsService::class)->store($request);
    }

    /**
     * Menampilkan detail berita
     * 
     * @param int $id
     * @return View
     */
    public function newsShow(int $id): View
    {
        $data = app(\App\Services\KoordinatorRedaksi\NewsService::class)->show($id);
        return view('koordinator-redaksi.news.show', $data);
    }

    /**
     * Menampilkan form untuk mengedit berita
     * 
     * @param int $id
     * @return View
     */
    public function newsEdit(int $id): View
    {
        $data = app(\App\Services\KoordinatorRedaksi\NewsService::class)->edit($id);
        return view('koordinator-redaksi.news.edit', $data + [
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts(),
        ]);
    }

    /**
     * Memperbarui berita yang sudah ada
     * 
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function newsUpdate(Request $request, int $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorRedaksi\NewsService::class)->update($request, $id);
    }

    /**
     * Menghapus berita
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function newsDestroy(int $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorRedaksi\NewsService::class)->destroy($id);
    }
}

