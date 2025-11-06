<?php

namespace App\Http\Controllers;

use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorJurnalistik\BriefService;

class BriefController extends Controller
{
    private BriefService $briefService;

    public function __construct(BriefService $briefService)
    {
        $this->briefService = $briefService;
    }

    /**
     * Display a listing of briefs.
     */
    public function index(): View
    {
        $data = $this->briefService->index();
        return view('koordinator-jurnalistik.briefs.index', $data);
    }

    /**
     * Show the form for creating a new brief.
     */
    public function create(): View
    {
        $data = $this->briefService->create();
        return view('koordinator-jurnalistik.briefs.create', $data);
    }

    /**
     * Store a newly created brief in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->briefService->store($request);
    }

    /**
     * Display the specified brief.
     */
    public function show(Brief $brief): View
    {
        $data = $this->briefService->show($brief);
        return view('koordinator-jurnalistik.briefs.show', $data);
    }

    /**
     * Show the form for editing the specified brief.
     */
    public function edit(Brief $brief): View
    {
        $data = $this->briefService->edit($brief);
        return view('koordinator-jurnalistik.briefs.edit', $data);
    }

    /**
     * Update the specified brief in storage.
     */
    public function update(Request $request, Brief $brief): RedirectResponse
    {
        return $this->briefService->update($request, $brief);
    }

    /**
     * Remove the specified brief from storage.
     */
    public function destroy(Brief $brief): RedirectResponse
    {
        return $this->briefService->destroy($brief);
    }
}