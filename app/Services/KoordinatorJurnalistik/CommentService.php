<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentService
{
    public function index(): array
    {
        $comments = Comment::with('news')->latest()->paginate(20);
        
        return compact('comments');
    }

    public function store(Request $request, News $news): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
        ]);

        $news->comments()->create([
            'name' => $request->name,
            'email' => $request->email,
            'content' => $request->content,
        ]);
        
        return redirect()->back()
            ->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        
        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus!');
    }
}


