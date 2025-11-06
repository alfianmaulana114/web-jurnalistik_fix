<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\KoordinatorJurnalistik\CommentService;

class CommentController extends Controller
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, News $news): RedirectResponse
    {
        return $this->commentService->store($request, $news);
    }

    /**
     * Display a listing of the comments for admin.
     */
    public function index(): View
    {
        $data = $this->commentService->index();
        return view('admin.comments.index', $data);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        return $this->commentService->destroy($comment);
    }
}