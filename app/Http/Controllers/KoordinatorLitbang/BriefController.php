<?php

namespace App\Http\Controllers\KoordinatorLitbang;

use App\Http\Controllers\Controller;
use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BriefController extends Controller
{
    private \App\Services\KoordinatorLitbang\BriefService $briefService;

    public function __construct(\App\Services\KoordinatorLitbang\BriefService $briefService)
    {
        $this->briefService = $briefService;
    }

    public function index(): View
    {
        $data = $this->briefService->index();
        return view('koordinator-litbang.briefs.index', $data);
    }

    public function create(): View
    {
        $data = $this->briefService->create();
        return view('koordinator-litbang.briefs.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->briefService->store($request);
    }

    public function show(Brief $brief): View
    {
        $data = $this->briefService->show($brief);
        return view('koordinator-litbang.briefs.show', $data);
    }

    public function edit(Brief $brief): View
    {
        $data = $this->briefService->edit($brief);
        return view('koordinator-litbang.briefs.edit', $data);
    }

    public function update(Request $request, Brief $brief): RedirectResponse
    {
        return $this->briefService->update($request, $brief);
    }

    public function destroy(Brief $brief): RedirectResponse
    {
        return $this->briefService->destroy($brief);
    }
}