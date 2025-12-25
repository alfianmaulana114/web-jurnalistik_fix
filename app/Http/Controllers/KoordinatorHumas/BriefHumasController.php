<?php

namespace App\Http\Controllers\KoordinatorHumas;

use App\Http\Controllers\Controller;
use App\Models\BriefHumas;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorHumas\BriefHumasService;

class BriefHumasController extends Controller
{
    private BriefHumasService $service;

    public function __construct(BriefHumasService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $data = $this->service->index($request);
        return view('koordinator-humas.brief-humas.index', $data);
    }

    public function create(): View
    {
        $data = $this->service->create();
        return view('koordinator-humas.brief-humas.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->service->store($request);
    }

    public function show(BriefHumas $briefHumas): View
    {
        $data = $this->service->show($briefHumas);
        return view('koordinator-humas.brief-humas.show', $data);
    }

    public function edit(BriefHumas $briefHumas): View
    {
        $data = $this->service->edit($briefHumas);
        return view('koordinator-humas.brief-humas.edit', $data);
    }

    public function update(Request $request, BriefHumas $briefHumas): RedirectResponse
    {
        return $this->service->update($request, $briefHumas);
    }

    public function destroy(BriefHumas $briefHumas): RedirectResponse
    {
        return $this->service->destroy($briefHumas);
    }
}

