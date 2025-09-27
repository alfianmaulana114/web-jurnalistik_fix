<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
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

    /**
     * Display a listing of the comments for admin.
     */
    public function index()
    {
        $comments = Comment::with('news')->latest()->paginate(20);
        
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        
        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus!');
    }
}