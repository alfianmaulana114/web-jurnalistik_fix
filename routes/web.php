<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Public Routes
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::post('/berita/{news}/komentar', [CommentController::class, 'store'])->name('comments.store');

// Admin Routes (tanpa autentikasi untuk sementara)
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);

    // News Management
    Route::get('/berita', [NewsController::class, 'dashboard'])->name('news.index');
    Route::get('/berita/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/berita', [NewsController::class, 'store'])->name('news.store');
    Route::get('/berita/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/berita/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/berita/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::post('/upload-image', [NewsController::class, 'uploadImage'])->name('upload.image');

    // Comment Management
    Route::get('/komentar', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/komentar/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
