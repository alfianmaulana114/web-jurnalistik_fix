<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorJurnalistik\DesignService;

class DesignController extends Controller
{
    private DesignService $designService;

    public function __construct(DesignService $designService)
    {
        $this->designService = $designService;
    }

    /**
     * Display a listing of designs.
     */
    public function index(): View
    {
        $data = $this->designService->index();
        return view('koordinator-jurnalistik.designs.index', $data);
    }

    /**
     * Show the form for creating a new design.
     */
    public function create(): View
    {
        $data = $this->designService->create();
        return view('koordinator-jurnalistik.designs.create', $data);
    }

    /**
     * Store a newly created design in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->designService->store($request);
    }

    /**
     * Display the specified design.
     */
    public function show(Design $design): View
    {
        $data = $this->designService->show($design);
        return view('koordinator-jurnalistik.designs.show', $data);
    }

    /**
     * Show the form for editing the specified design.
     */
    public function edit(Design $design): View
    {
        $data = $this->designService->edit($design);
        return view('koordinator-jurnalistik.designs.edit', $data);
    }

    /**
     * Update the specified design in storage.
     */
    public function update(Request $request, Design $design): RedirectResponse
    {
        return $this->designService->update($request, $design);
    }

    /**
     * Remove the specified design from storage.
     */
    public function destroy(Design $design): RedirectResponse
    {
        return $this->designService->destroy($design);
    }
}