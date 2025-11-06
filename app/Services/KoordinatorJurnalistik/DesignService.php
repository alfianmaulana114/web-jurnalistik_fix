<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Design;
use App\Models\Content;
use App\Models\Proker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DesignService
{
    public function index(): array
    {
        $designs = Design::with(['content', 'proker', 'creator', 'reviewer'])
            ->latest()
            ->paginate(10);
            
        return compact('designs');
    }

    public function create(): array
    {
        $contents = Content::approved()->get();
        $prokers = Proker::active()->get();
        $users = User::all();
        return compact('contents', 'prokers', 'users');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_desain' => ['required', Rule::in(array_keys(Design::getTypes()))],
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,pdf,ai,psd|max:10240',
            'dimensi' => 'nullable|string',
            'status' => ['required', Rule::in(array_keys(Design::getAllStatuses()))],
            'catatan_revisi' => 'nullable|string',
            'content_id' => 'nullable|exists:contents,id',
            'proker_id' => 'nullable|exists:prokers,id',
            'reviewed_by' => 'nullable|exists:users,id',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('designs', $fileName, 'public');
            
            $validated['file_path'] = $filePath;
            $validated['file_name'] = $fileName;
            $validated['file_size'] = $file->getSize();
        }

        // Assume current user is koordinator jurnalistik (ID 1 for now)
        $validated['created_by'] = 1;

        Design::create($validated);

        return redirect()->route('koordinator-jurnalistik.designs.index')
            ->with('success', 'Desain berhasil ditambahkan.');
    }

    public function show(Design $design): array
    {
        $design->load(['content', 'proker', 'creator', 'reviewer']);
        return compact('design');
    }

    public function edit(Design $design): array
    {
        $contents = Content::approved()->get();
        $prokers = Proker::active()->get();
        $users = User::all();
        return compact('design', 'contents', 'prokers', 'users');
    }

    public function update(Request $request, Design $design): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_desain' => ['required', Rule::in(array_keys(Design::getTypes()))],
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg,pdf,ai,psd|max:10240',
            'dimensi' => 'nullable|string',
            'status' => ['required', Rule::in(array_keys(Design::getAllStatuses()))],
            'catatan_revisi' => 'nullable|string',
            'content_id' => 'nullable|exists:contents,id',
            'proker_id' => 'nullable|exists:prokers,id',
            'reviewed_by' => 'nullable|exists:users,id',
        ]);

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file
            if ($design->file_path && Storage::disk('public')->exists($design->file_path)) {
                Storage::disk('public')->delete($design->file_path);
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('designs', $fileName, 'public');
            
            $validated['file_path'] = $filePath;
            $validated['file_name'] = $fileName;
            $validated['file_size'] = $file->getSize();
        }

        $design->update($validated);

        return redirect()->route('koordinator-jurnalistik.designs.index')
            ->with('success', 'Desain berhasil diperbarui.');
    }

    public function destroy(Design $design): RedirectResponse
    {
        // Delete file from storage
        if ($design->file_path && Storage::disk('public')->exists($design->file_path)) {
            Storage::disk('public')->delete($design->file_path);
        }
        
        $design->delete();

        return redirect()->route('koordinator-jurnalistik.designs.index')
            ->with('success', 'Desain berhasil dihapus.');
    }
}


