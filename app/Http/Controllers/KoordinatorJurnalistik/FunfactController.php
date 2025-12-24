<?php

namespace App\Http\Controllers\KoordinatorJurnalistik;

use App\Http\Controllers\Controller;
use App\Models\Funfact;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorJurnalistik\FunfactService;

/**
 * Controller untuk manajemen Funfact
 * 
 * Menangani semua request yang terkait dengan funfact
 */
class FunfactController extends Controller
{
    private FunfactService $funfactService;

    /**
     * Constructor dengan dependency injection
     * 
     * @param FunfactService $funfactService
     */
    public function __construct(FunfactService $funfactService)
    {
        $this->funfactService = $funfactService;
    }

    /**
     * Display a listing of funfacts.
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $data = $this->funfactService->index($request);
        return view('koordinator-jurnalistik.funfacts.index', $data);
    }

    /**
     * Show the form for creating a new funfact.
     * 
     * @return View
     */
    public function create(): View
    {
        $data = $this->funfactService->create();
        return view('koordinator-jurnalistik.funfacts.create', $data);
    }

    /**
     * Store a newly created funfact in storage.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->funfactService->store($request);
    }

    /**
     * Display the specified funfact.
     * 
     * @param Funfact $funfact
     * @return View
     */
    public function show(Funfact $funfact): View
    {
        $data = $this->funfactService->show($funfact);
        return view('koordinator-jurnalistik.funfacts.show', $data);
    }

    /**
     * Show the form for editing the specified funfact.
     * 
     * @param Funfact $funfact
     * @return View
     */
    public function edit(Funfact $funfact): View
    {
        $data = $this->funfactService->edit($funfact);
        return view('koordinator-jurnalistik.funfacts.edit', $data);
    }

    /**
     * Update the specified funfact in storage.
     * 
     * @param Request $request
     * @param Funfact $funfact
     * @return RedirectResponse
     */
    public function update(Request $request, Funfact $funfact): RedirectResponse
    {
        return $this->funfactService->update($request, $funfact);
    }

    /**
     * Remove the specified funfact from storage.
     * 
     * @param Funfact $funfact
     * @return RedirectResponse
     */
    public function destroy(Funfact $funfact): RedirectResponse
    {
        return $this->funfactService->destroy($funfact);
    }
}

