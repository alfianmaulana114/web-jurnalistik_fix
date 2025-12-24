<?php

namespace App\Http\Controllers\KoordinatorLitbang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PenjadwalanController extends Controller
{
    public function index(Request $request): View
    {
        $data = app(\App\Services\KoordinatorLitbang\PenjadwalanService::class)->index($request);
        return view('koordinator-litbang.penjadwalan.index', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        return app(\App\Services\KoordinatorLitbang\PenjadwalanService::class)->store($request);
    }

    public function edit(int $id): View
    {
        $data = app(\App\Services\KoordinatorLitbang\PenjadwalanService::class)->edit($id);
        return view('koordinator-litbang.penjadwalan.edit', $data);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorLitbang\PenjadwalanService::class)->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return app(\App\Services\KoordinatorLitbang\PenjadwalanService::class)->destroy($id);
    }
}