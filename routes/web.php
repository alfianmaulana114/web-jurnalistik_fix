<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TempImageController;
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
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::post('/berita/{news}/komentar', [CommentController::class, 'store'])->name('comments.store');

// Admin Routes (tanpa autentikasi untuk sementara)
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);

    // News Management
    Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
    Route::get('/berita/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/berita', [NewsController::class, 'store'])->name('news.store');
    Route::get('/berita/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/berita/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/berita/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::post('/upload-image', [NewsController::class, 'uploadImage'])->name('upload.image');
    Route::post('/admin/temp-images', [TempImageController::class, 'store'])->name('admin.temp-images.store');
    

    // Comment Management
    Route::get('/komentar', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/komentar/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    // Di dalam group admin
    Route::post('/temp-images', [TempImageController::class, 'store'])->name('temp-images.store');
    Route::post('/temp-images/crop', [TempImageController::class, 'crop'])->name('temp-images.crop');
});
