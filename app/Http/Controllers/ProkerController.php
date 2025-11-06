<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorJurnalistik\ProkerService;

class ProkerController extends Controller
{
    private ProkerService $prokerService;

    public function __construct(ProkerService $prokerService)
    {
        $this->prokerService = $prokerService;
    }

    /**
     * Display a listing of prokers.
     */
    public function index(): View
    {
        $data = $this->prokerService->index();
        return view('koordinator-jurnalistik.prokers.index', $data);
    }

    /**
     * Show the form for creating a new proker.
     */
    public function create(): View
    {
        $data = $this->prokerService->create();
        return view('koordinator-jurnalistik.prokers.create', $data);
    }

    /**
     * Store a newly created proker in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->prokerService->store($request);
    }

    /**
     * Display the specified proker.
     */
    public function show(Proker $proker): View
    {
        $data = $this->prokerService->show($proker);
        return view('koordinator-jurnalistik.prokers.show', $data);
    }

    /**
     * Show the form for editing the specified proker.
     */
    public function edit(Proker $proker): View
    {
        $data = $this->prokerService->edit($proker);
        return view('koordinator-jurnalistik.prokers.edit', $data);
    }

    /**
     * Update the specified proker in storage.
     */
    public function update(Request $request, Proker $proker): RedirectResponse
    {
        return $this->prokerService->update($request, $proker);
    }

    /**
     * Remove the specified proker from storage.
     */
    public function destroy(Proker $proker): RedirectResponse
    {
        return $this->prokerService->destroy($proker);
    }
}