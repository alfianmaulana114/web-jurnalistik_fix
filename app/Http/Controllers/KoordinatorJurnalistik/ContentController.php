<?php

namespace App\Http\Controllers\KoordinatorJurnalistik;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorJurnalistik\ContentService;

class ContentController extends Controller
{
    private ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Display a listing of captions.
     */
    public function index(Request $request): View
    {
        $data = $this->contentService->index($request);
        return view('koordinator-jurnalistik.contents.index', $data);
    }

    /**
     * Show the form for creating a new caption.
     */
    public function create(Request $request): View
    {
        $data = $this->contentService->create($request);
        return view('koordinator-jurnalistik.contents.create', $data);
    }

    /**
     * Store a newly created caption in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->contentService->store($request);
    }

    /**
     * Display the specified caption.
     */
    public function show(Content $content): View
    {
        $data = $this->contentService->show($content);
        return view('koordinator-jurnalistik.contents.show', $data);
    }

    /**
     * Show the form for editing the specified caption.
     */
    public function edit(Content $content): View
    {
        $data = $this->contentService->edit($content);
        return view('koordinator-jurnalistik.contents.edit', $data);
    }

    /**
     * Update the specified caption in storage.
     */
    public function update(Request $request, Content $content): RedirectResponse
    {
        return $this->contentService->update($request, $content);
    }

    /**
     * Remove the specified caption from storage.
     */
    public function destroy(Content $content): RedirectResponse
    {
        return $this->contentService->destroy($content);
    }

    /**
     * Update caption status (for AJAX requests)
     */
    public function updateStatus(Request $request, Content $content)
    {
        return $this->contentService->updateStatus($request, $content);
    }
}