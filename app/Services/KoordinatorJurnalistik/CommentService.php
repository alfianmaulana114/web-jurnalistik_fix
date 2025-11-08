<?php

namespace App\Services\KoordinatorJurnalistik;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CommentService
{
    public function index(): array
    {
        $comments = Comment::with('news')->latest()->paginate(20);
        
        return compact('comments');
    }

    public function store(Request $request, News $news): RedirectResponse
    {
        // Rate limiting untuk mencegah spam
        $this->ensureIsNotRateLimited($request);

        // Validasi dengan sanitization untuk security
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\.\-]+$/',
            'email' => 'required|email|max:255',
            'content' => 'required|string|max:2000',
        ], [
            'name.regex' => 'Nama hanya boleh mengandung huruf, angka, spasi, titik, dan tanda hubung.',
            'content.max' => 'Komentar maksimal 2000 karakter.',
        ]);

        // Sanitize input untuk mencegah XSS
        $comment = $news->comments()->create([
            'name' => strip_tags(trim($validated['name'])),
            'email' => filter_var(trim($validated['email']), FILTER_SANITIZE_EMAIL),
            'content' => strip_tags(trim($validated['content'])),
        ]);

        // Hit rate limiter setelah sukses
        RateLimiter::hit($this->throttleKey($request));
        
        return redirect()->back()
            ->with('success', 'Komentar berhasil ditambahkan!')
            ->withFragment('comment-' . $comment->id);
    }

    /**
     * Ensure the comment request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        $key = $this->throttleKey($request);
        
        // Maksimal 5 komentar per 60 menit per IP
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak komentar. Silakan coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return 'comment:' . $request->ip() . ':' . $request->input('email');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        
        return redirect()->back()
            ->with('success', 'Komentar berhasil dihapus!');
    }
}


