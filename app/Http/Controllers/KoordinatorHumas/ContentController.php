<?php

namespace App\Http\Controllers\KoordinatorHumas;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorHumas\ContentService;

class ContentController extends Controller
{
    private ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Display a listing of contents.
     */
    public function index(): View
    {
        $data = $this->contentService->index();
        return view('koordinator-humas.contents.index', $data);
    }

    /**
     * Show the form for creating a new content.
     */
    public function create(Request $request): View
    {
        $data = $this->contentService->create($request);
        return view('koordinator-humas.contents.create', $data);
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->contentService->store($request);
    }

    /**
     * Display the specified content.
     */
    public function show(Content $content): View
    {
        $data = $this->contentService->show($content);
        return view('koordinator-humas.contents.show', $data);
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Content $content): View
    {
        $data = $this->contentService->edit($content);
        return view('koordinator-humas.contents.edit', $data);
    }

    /**
     * Update the specified content in storage.
     */
    public function update(Request $request, Content $content): RedirectResponse
    {
        return $this->contentService->update($request, $content);
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Content $content): RedirectResponse
    {
        return $this->contentService->destroy($content);
    }
}

