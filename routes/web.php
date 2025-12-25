<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KoordinatorJurnalistik\KoordinatorJurnalistikController;
use App\Http\Controllers\KoordinatorJurnalistik\ProkerController;
use App\Http\Controllers\KoordinatorJurnalistik\BriefController;
use App\Http\Controllers\KoordinatorJurnalistik\ContentController;
use App\Http\Controllers\KoordinatorJurnalistik\DesignController;
use App\Http\Controllers\KoordinatorJurnalistik\UserController;
use App\Http\Controllers\KoordinatorJurnalistik\FunfactController as KoordinatorJurnalistikFunfactController;
use App\Http\Controllers\KoordinatorRedaksi\KoordinatorRedaksiController;
use App\Http\Controllers\KoordinatorRedaksi\PenjadwalanController;
use App\Http\Controllers\KoordinatorRedaksi\FunfactController as KoordinatorRedaksiFunfactController;
use App\Http\Controllers\KoordinatorLitbang\KoordinatorLitbangController;
use App\Http\Controllers\KoordinatorLitbang\PenjadwalanController as KoordinatorLitbangPenjadwalanController;
use App\Http\Controllers\KoordinatorLitbang\ReadOnlyController as KoordinatorLitbangReadOnlyController;
use App\Http\Controllers\KoordinatorHumas\KoordinatorHumasController as KoordinatorHumasController;
use App\Http\Controllers\KoordinatorHumas\ContentController as KoordinatorHumasContentController;
use App\Http\Controllers\KoordinatorHumas\PenjadwalanController as KoordinatorHumasPenjadwalanController;
use App\Http\Controllers\KoordinatorHumas\ReadOnlyController as KoordinatorHumasReadOnlyController;
use App\Http\Controllers\KoordinatorMediaKreatif\ReadOnlyController as KoordinatorMediaKreatifReadOnlyController;
use App\Http\Controllers\KoordinatorLitbang\BriefController as KoordinatorLitbangBriefController;
use App\Http\Controllers\Sekretaris\SekretarisController;
use App\Http\Controllers\Sekretaris\ReadOnlyController as SekretarisReadOnlyController;
use App\Http\Controllers\Sekretaris\AbsenController;
use App\Http\Controllers\Bendahara\BendaharaController;
use App\Http\Controllers\Bendahara\ReadOnlyController;
use App\Http\Controllers\KoordinatorRedaksi\ReadOnlyController as KoordinatorRedaksiReadOnlyController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\TempImageController;
use App\Models\News;
use Illuminate\Support\Facades\Route;

// Home Route - Menggunakan HomeController seperti sebelumnya
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes - Using Laravel Breeze
require __DIR__.'/auth.php';

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Koordinator Jurnalistik Routes
Route::prefix('koordinator-jurnalistik')->name('koordinator-jurnalistik.')->middleware(['auth', 'role:koordinator_jurnalistik'])->group(function () {
    Route::get('/dashboard', [KoordinatorJurnalistikController::class, 'dashboard'])->name('dashboard');

    // Finance: Riwayat Kas Anggota & Laporan Keuangan (read-only)
    Route::get('/kas-anggota/riwayat', [KoordinatorJurnalistikController::class, 'kasAnggotaRiwayat'])->name('kas-anggota.riwayat');
    Route::get('/laporan', [KoordinatorJurnalistikController::class, 'laporanKeuangan'])->name('laporan.index');
    Route::get('/laporan/export-excel', [KoordinatorJurnalistikController::class, 'exportExcel'])->name('laporan.export-excel');
    // Sekretaris (Read-Only): Notulensi & Absen
    Route::get('/sekretaris/notulensi', [KoordinatorJurnalistikController::class, 'sekretarisNotulensiIndex'])->name('sekretaris.notulensi.index');
    Route::get('/sekretaris/notulensi/{notulensi}', [KoordinatorJurnalistikController::class, 'sekretarisNotulensiShow'])->name('sekretaris.notulensi.show');
    Route::get('/sekretaris/notulensi/{notulensi}/download', [KoordinatorJurnalistikController::class, 'sekretarisNotulensiDownload'])->name('sekretaris.notulensi.download');
    Route::get('/sekretaris/absen', [KoordinatorJurnalistikController::class, 'sekretarisAbsenIndex'])->name('sekretaris.absen.index');
    
    // News Routes
    Route::get('/news', [KoordinatorJurnalistikController::class, 'newsIndex'])->name('news.index');
    Route::get('/news/create', [KoordinatorJurnalistikController::class, 'newsCreate'])->name('news.create');
    Route::post('/news', [KoordinatorJurnalistikController::class, 'newsStore'])->name('news.store');
    Route::get('/news/{id}', [KoordinatorJurnalistikController::class, 'newsShow'])->name('news.show');
    Route::get('/news/{id}/edit', [KoordinatorJurnalistikController::class, 'newsEdit'])->name('news.edit');
    Route::put('/news/{id}', [KoordinatorJurnalistikController::class, 'newsUpdate'])->name('news.update');
    Route::delete('/news/{id}', [KoordinatorJurnalistikController::class, 'newsDestroy'])->name('news.destroy');
    Route::post('/news/upload-image', [KoordinatorJurnalistikController::class, 'uploadImage'])->name('news.upload-image');
    
    // Proker Routes
    Route::resource('prokers', ProkerController::class);
    
    // Brief Routes
    Route::resource('briefs', BriefController::class);
    Route::resource('brief-humas', \App\Http\Controllers\KoordinatorJurnalistik\BriefHumasController::class);
    
    // Content Routes
    Route::resource('contents', ContentController::class);
    
    // Design Routes
    Route::resource('designs', DesignController::class);
    
    // User Routes
    Route::resource('users', UserController::class);
    
    // User Management Routes (Register & Password Reset)
    Route::get('/users/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('users.register');
    Route::post('/users/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('users.register.store');
    Route::get('/users/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('users.forgot-password');
    Route::post('/users/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('users.forgot-password.store');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    
    // Funfact Routes
    Route::resource('funfacts', KoordinatorJurnalistikFunfactController::class);
    
    
    
    // Temp Image Routes
    Route::post('/temp-images', [TempImageController::class, 'store'])->name('temp-images.store');
    Route::delete('/temp-images/{tempImage}', [TempImageController::class, 'destroy'])->name('temp-images.destroy');
});

// Koordinator Redaksi Routes
Route::prefix('koordinator-redaksi')->name('koordinator-redaksi.')->middleware(['auth', 'role:koordinator_redaksi'])->group(function () {
    Route::get('/dashboard', [KoordinatorRedaksiController::class, 'dashboard'])->name('dashboard');
    
    // News Routes
    Route::get('/news', [KoordinatorRedaksiController::class, 'newsIndex'])->name('news.index');
    Route::get('/news/create', [KoordinatorRedaksiController::class, 'newsCreate'])->name('news.create');
    Route::post('/news', [KoordinatorRedaksiController::class, 'newsStore'])->name('news.store');
    Route::get('/news/{id}', [KoordinatorRedaksiController::class, 'newsShow'])->name('news.show');
    Route::get('/news/{id}/edit', [KoordinatorRedaksiController::class, 'newsEdit'])->name('news.edit');
    Route::put('/news/{id}', [KoordinatorRedaksiController::class, 'newsUpdate'])->name('news.update');
    Route::delete('/news/{id}', [KoordinatorRedaksiController::class, 'newsDestroy'])->name('news.destroy');
    
    // Penjadwalan Routes
    Route::resource('penjadwalan', PenjadwalanController::class);
    
    // Funfact Routes
    Route::resource('funfacts', KoordinatorRedaksiFunfactController::class);
    
    // Temp Image Routes
    Route::post('/temp-images', [TempImageController::class, 'store'])->name('temp-images.store');
    Route::delete('/temp-images/{tempImage}', [TempImageController::class, 'destroy'])->name('temp-images.destroy');

    // Read-Only Routes untuk melihat fitur koordinator-jurnalistik
    Route::prefix('view')->name('view.')->group(function () {
        // Keuangan (Read-Only)
        Route::get('/laporan', [KoordinatorRedaksiReadOnlyController::class, 'laporanKeuangan'])->name('laporan.index');
        Route::get('/laporan/export-excel', [KoordinatorJurnalistikController::class, 'exportExcel'])->name('laporan.export-excel');

        // Proker Routes (Read-Only)
        Route::get('/prokers', [KoordinatorRedaksiReadOnlyController::class, 'prokersIndex'])->name('prokers.index');
        Route::get('/prokers/{proker}', [KoordinatorRedaksiReadOnlyController::class, 'prokersShow'])->name('prokers.show');

        // Brief Routes (Read-Only)
        Route::get('/briefs', [KoordinatorRedaksiReadOnlyController::class, 'briefsIndex'])->name('briefs.index');
        Route::get('/briefs/{brief}', [KoordinatorRedaksiReadOnlyController::class, 'briefsShow'])->name('briefs.show');

        // Content Routes (Read-Only)
        Route::get('/contents', [KoordinatorRedaksiReadOnlyController::class, 'contentsIndex'])->name('contents.index');
        Route::get('/contents/{content}', [KoordinatorRedaksiReadOnlyController::class, 'contentsShow'])->name('contents.show');

        // Design Routes (Read-Only)
        Route::get('/designs', [KoordinatorRedaksiReadOnlyController::class, 'designsIndex'])->name('designs.index');
        Route::get('/designs/{design}', [KoordinatorRedaksiReadOnlyController::class, 'designsShow'])->name('designs.show');

        // User Routes (Read-Only)
        Route::get('/users', [KoordinatorRedaksiReadOnlyController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}', [KoordinatorRedaksiReadOnlyController::class, 'usersShow'])->name('users.show');

        // Brief Humas Routes (Read-Only)
        Route::get('/brief-humas', [KoordinatorRedaksiReadOnlyController::class, 'briefHumasIndex'])->name('brief-humas.index');
        Route::get('/brief-humas/{briefHumas}', [KoordinatorRedaksiReadOnlyController::class, 'briefHumasShow'])->name('brief-humas.show');

        // Sekretaris: Notulensi & Absen (Read-Only)
        Route::get('/sekretaris/notulensi', [KoordinatorRedaksiReadOnlyController::class, 'sekretarisNotulensiIndex'])->name('sekretaris.notulensi.index');
        Route::get('/sekretaris/notulensi/{notulensi}', [KoordinatorRedaksiReadOnlyController::class, 'sekretarisNotulensiShow'])->name('sekretaris.notulensi.show');
        Route::get('/sekretaris/notulensi/{notulensi}/download', [KoordinatorRedaksiReadOnlyController::class, 'sekretarisNotulensiDownload'])->name('sekretaris.notulensi.download');
        Route::get('/sekretaris/absen', [KoordinatorRedaksiReadOnlyController::class, 'sekretarisAbsenIndex'])->name('sekretaris.absen.index');
    });
});

// Koordinator Litbang Routes
Route::prefix('koordinator-litbang')->name('koordinator-litbang.')->middleware(['auth', 'role:koordinator_litbang'])->group(function () {
    Route::get('/dashboard', [KoordinatorLitbangController::class, 'dashboard'])->name('dashboard');

    // Brief Routes (sama seperti koordinator jurnalistik)
    Route::resource('briefs', KoordinatorLitbangBriefController::class);

    // Penjadwalan Routes (untuk anggota litbang)
    Route::resource('penjadwalan', KoordinatorLitbangPenjadwalanController::class);

    // Read-Only Routes untuk melihat fitur bendahara dan lainnya
    Route::prefix('view')->name('view.')->group(function () {
        // Keuangan (Read-Only)
        Route::get('/laporan', [KoordinatorLitbangReadOnlyController::class, 'laporanKeuangan'])->name('laporan.index');
        Route::get('/laporan/export-excel', [KoordinatorLitbangReadOnlyController::class, 'exportExcel'])->name('laporan.export-excel');

        // News Routes (Read-Only)
        Route::get('/news', [KoordinatorLitbangReadOnlyController::class, 'newsIndex'])->name('news.index');
        Route::get('/news/{id}', [KoordinatorLitbangReadOnlyController::class, 'newsShow'])->name('news.show');

        // Proker Routes (Read-Only)
        Route::get('/prokers', [KoordinatorLitbangReadOnlyController::class, 'prokersIndex'])->name('prokers.index');
        Route::get('/prokers/{proker}', [KoordinatorLitbangReadOnlyController::class, 'prokersShow'])->name('prokers.show');

        // Content Routes (Read-Only)
        Route::get('/contents', [KoordinatorLitbangReadOnlyController::class, 'contentsIndex'])->name('contents.index');
        Route::get('/contents/{content}', [KoordinatorLitbangReadOnlyController::class, 'contentsShow'])->name('contents.show');

        // Design Routes (Read-Only)
        Route::get('/designs', [KoordinatorLitbangReadOnlyController::class, 'designsIndex'])->name('designs.index');
        Route::get('/designs/{design}', [KoordinatorLitbangReadOnlyController::class, 'designsShow'])->name('designs.show');

        // Funfact Routes (Read-Only)
        Route::get('/funfacts', [KoordinatorLitbangReadOnlyController::class, 'funfactsIndex'])->name('funfacts.index');
        Route::get('/funfacts/{funfact}', [KoordinatorLitbangReadOnlyController::class, 'funfactsShow'])->name('funfacts.show');

        // User Routes (Read-Only)
        Route::get('/users', [KoordinatorLitbangReadOnlyController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}', [KoordinatorLitbangReadOnlyController::class, 'usersShow'])->name('users.show');

        // Brief Humas Routes (Read-Only)
        Route::get('/brief-humas', [KoordinatorLitbangReadOnlyController::class, 'briefHumasIndex'])->name('brief-humas.index');
        Route::get('/brief-humas/{briefHumas}', [KoordinatorLitbangReadOnlyController::class, 'briefHumasShow'])->name('brief-humas.show');

        // Sekretaris: Notulensi & Absen (Read-Only)
        Route::get('/sekretaris/notulensi', [KoordinatorLitbangReadOnlyController::class, 'sekretarisNotulensiIndex'])->name('sekretaris.notulensi.index');
        Route::get('/sekretaris/notulensi/{notulensi}', [KoordinatorLitbangReadOnlyController::class, 'sekretarisNotulensiShow'])->name('sekretaris.notulensi.show');
        Route::get('/sekretaris/notulensi/{notulensi}/download', [KoordinatorLitbangReadOnlyController::class, 'sekretarisNotulensiDownload'])->name('sekretaris.notulensi.download');
        Route::get('/sekretaris/absen', [KoordinatorLitbangReadOnlyController::class, 'sekretarisAbsenIndex'])->name('sekretaris.absen.index');
    });
});

// Koordinator Humas Routes
Route::prefix('koordinator-humas')->name('koordinator-humas.')->middleware(['auth', 'role:koordinator_humas'])->group(function () {
    Route::get('/dashboard', [KoordinatorHumasController::class, 'dashboard'])->name('dashboard');
    Route::resource('contents', KoordinatorHumasContentController::class);
    Route::resource('penjadwalan', KoordinatorHumasPenjadwalanController::class);
    Route::resource('brief-humas', \App\Http\Controllers\KoordinatorHumas\BriefHumasController::class);

    // Read-Only Routes untuk melihat fitur bendahara dan lainnya
    Route::prefix('view')->name('view.')->group(function () {
        // Keuangan (Read-Only)
        Route::get('/laporan', [KoordinatorHumasReadOnlyController::class, 'laporanKeuangan'])->name('laporan.index');
        Route::get('/laporan/export-excel', [KoordinatorHumasReadOnlyController::class, 'exportExcel'])->name('laporan.export-excel');

        // News Routes (Read-Only)
        Route::get('/news', [KoordinatorHumasReadOnlyController::class, 'newsIndex'])->name('news.index');
        Route::get('/news/{id}', [KoordinatorHumasReadOnlyController::class, 'newsShow'])->name('news.show');

        // Proker Routes (Read-Only)
        Route::get('/prokers', [KoordinatorHumasReadOnlyController::class, 'prokersIndex'])->name('prokers.index');
        Route::get('/prokers/{proker}', [KoordinatorHumasReadOnlyController::class, 'prokersShow'])->name('prokers.show');

        // Brief Routes (Read-Only)
        Route::get('/briefs', [KoordinatorHumasReadOnlyController::class, 'briefsIndex'])->name('briefs.index');
        Route::get('/briefs/{brief}', [KoordinatorHumasReadOnlyController::class, 'briefsShow'])->name('briefs.show');

        // Design Routes (Read-Only)
        Route::get('/designs', [KoordinatorHumasReadOnlyController::class, 'designsIndex'])->name('designs.index');
        Route::get('/designs/{design}', [KoordinatorHumasReadOnlyController::class, 'designsShow'])->name('designs.show');

        // Funfact Routes (Read-Only)
        Route::get('/funfacts', [KoordinatorHumasReadOnlyController::class, 'funfactsIndex'])->name('funfacts.index');
        Route::get('/funfacts/{funfact}', [KoordinatorHumasReadOnlyController::class, 'funfactsShow'])->name('funfacts.show');

        // User Routes (Read-Only)
        Route::get('/users', [KoordinatorHumasReadOnlyController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}', [KoordinatorHumasReadOnlyController::class, 'usersShow'])->name('users.show');

        // Sekretaris: Notulensi & Absen (Read-Only)
        Route::get('/sekretaris/notulensi', [KoordinatorHumasReadOnlyController::class, 'sekretarisNotulensiIndex'])->name('sekretaris.notulensi.index');
        Route::get('/sekretaris/notulensi/{notulensi}', [KoordinatorHumasReadOnlyController::class, 'sekretarisNotulensiShow'])->name('sekretaris.notulensi.show');
        Route::get('/sekretaris/notulensi/{notulensi}/download', [KoordinatorHumasReadOnlyController::class, 'sekretarisNotulensiDownload'])->name('sekretaris.notulensi.download');
        Route::get('/sekretaris/absen', [KoordinatorHumasReadOnlyController::class, 'sekretarisAbsenIndex'])->name('sekretaris.absen.index');
    });
});

// Koordinator Media Kreatif Routes
Route::prefix('koordinator-media-kreatif')->name('koordinator-media-kreatif.')->middleware(['auth', 'role:koordinator_media_kreatif'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\KoordinatorMediaKreatif\KoordinatorMediaKreatifController::class, 'dashboard'])->name('dashboard');
    Route::resource('designs', \App\Http\Controllers\KoordinatorMediaKreatif\DesignController::class);
    Route::get('penjadwalan', [\App\Http\Controllers\KoordinatorMediaKreatif\PenjadwalanController::class, 'index'])->name('penjadwalan.index');
    Route::post('penjadwalan', [\App\Http\Controllers\KoordinatorMediaKreatif\PenjadwalanController::class, 'store'])->name('penjadwalan.store');
    Route::get('penjadwalan/{id}/edit', [\App\Http\Controllers\KoordinatorMediaKreatif\PenjadwalanController::class, 'edit'])->name('penjadwalan.edit');
    Route::put('penjadwalan/{id}', [\App\Http\Controllers\KoordinatorMediaKreatif\PenjadwalanController::class, 'update'])->name('penjadwalan.update');
    Route::delete('penjadwalan/{id}', [\App\Http\Controllers\KoordinatorMediaKreatif\PenjadwalanController::class, 'destroy'])->name('penjadwalan.destroy');

    // Read-Only Routes untuk melihat fitur bendahara dan lainnya
    Route::prefix('view')->name('view.')->group(function () {
        // Keuangan (Read-Only)
        Route::get('/laporan', [KoordinatorMediaKreatifReadOnlyController::class, 'laporanKeuangan'])->name('laporan.index');
        Route::get('/laporan/export-excel', [KoordinatorMediaKreatifReadOnlyController::class, 'exportExcel'])->name('laporan.export-excel');

        // News Routes (Read-Only)
        Route::get('/news', [KoordinatorMediaKreatifReadOnlyController::class, 'newsIndex'])->name('news.index');
        Route::get('/news/{id}', [KoordinatorMediaKreatifReadOnlyController::class, 'newsShow'])->name('news.show');

        // Proker Routes (Read-Only)
        Route::get('/prokers', [KoordinatorMediaKreatifReadOnlyController::class, 'prokersIndex'])->name('prokers.index');
        Route::get('/prokers/{proker}', [KoordinatorMediaKreatifReadOnlyController::class, 'prokersShow'])->name('prokers.show');

        // Brief Routes (Read-Only)
        Route::get('/briefs', [KoordinatorMediaKreatifReadOnlyController::class, 'briefsIndex'])->name('briefs.index');
        Route::get('/briefs/{brief}', [KoordinatorMediaKreatifReadOnlyController::class, 'briefsShow'])->name('briefs.show');

        // Content Routes (Read-Only)
        Route::get('/contents', [KoordinatorMediaKreatifReadOnlyController::class, 'contentsIndex'])->name('contents.index');
        Route::get('/contents/{content}', [KoordinatorMediaKreatifReadOnlyController::class, 'contentsShow'])->name('contents.show');

        // Design Routes (Read-Only)
        Route::get('/designs', [KoordinatorMediaKreatifReadOnlyController::class, 'designsIndex'])->name('designs.index');
        Route::get('/designs/{design}', [KoordinatorMediaKreatifReadOnlyController::class, 'designsShow'])->name('designs.show');

        // Funfact Routes (Read-Only)
        Route::get('/funfacts', [KoordinatorMediaKreatifReadOnlyController::class, 'funfactsIndex'])->name('funfacts.index');
        Route::get('/funfacts/{funfact}', [KoordinatorMediaKreatifReadOnlyController::class, 'funfactsShow'])->name('funfacts.show');

        // User Routes (Read-Only)
        Route::get('/users', [KoordinatorMediaKreatifReadOnlyController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}', [KoordinatorMediaKreatifReadOnlyController::class, 'usersShow'])->name('users.show');

        // Brief Humas Routes (Read-Only)
        Route::get('/brief-humas', [KoordinatorMediaKreatifReadOnlyController::class, 'briefHumasIndex'])->name('brief-humas.index');
        Route::get('/brief-humas/{briefHumas}', [KoordinatorMediaKreatifReadOnlyController::class, 'briefHumasShow'])->name('brief-humas.show');

        // Sekretaris: Notulensi & Absen (Read-Only)
        Route::get('/sekretaris/notulensi', [KoordinatorMediaKreatifReadOnlyController::class, 'sekretarisNotulensiIndex'])->name('sekretaris.notulensi.index');
        Route::get('/sekretaris/notulensi/{notulensi}', [KoordinatorMediaKreatifReadOnlyController::class, 'sekretarisNotulensiShow'])->name('sekretaris.notulensi.show');
        Route::get('/sekretaris/notulensi/{notulensi}/download', [KoordinatorMediaKreatifReadOnlyController::class, 'sekretarisNotulensiDownload'])->name('sekretaris.notulensi.download');
        Route::get('/sekretaris/absen', [KoordinatorMediaKreatifReadOnlyController::class, 'sekretarisAbsenIndex'])->name('sekretaris.absen.index');
    });
});

// Anggota Litbang Routes
Route::prefix('anggota-litbang')->name('anggota-litbang.')->middleware(['auth', 'role:anggota_litbang'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AnggotaLitbang\AnggotaLitbangController::class, 'dashboard'])->name('dashboard');
    Route::resource('briefs', \App\Http\Controllers\AnggotaLitbang\BriefController::class);
});

// Sekretaris Routes
Route::prefix('sekretaris')->name('sekretaris.')->middleware(['auth', 'role:sekretaris'])->group(function () {
    Route::get('/dashboard', [SekretarisController::class, 'dashboard'])->name('dashboard');

    // Finance: Riwayat Kas Anggota & Laporan Keuangan (read-only)
    Route::get('/kas-anggota/riwayat', [SekretarisController::class, 'kasAnggotaRiwayat'])->name('kas-anggota.riwayat');
    Route::get('/laporan', [SekretarisController::class, 'laporanKeuangan'])->name('laporan.index');
    Route::get('/laporan/export-excel', [SekretarisController::class, 'exportExcel'])->name('laporan.export-excel');
    
    // Notulensi Routes
    Route::get('/notulensi', [SekretarisController::class, 'notulensiIndex'])->name('notulensi.index');
    Route::get('/notulensi/create', [SekretarisController::class, 'notulensiCreate'])->name('notulensi.create');
    Route::post('/notulensi', [SekretarisController::class, 'notulensiStore'])->name('notulensi.store');
    Route::get('/notulensi/{notulensi}', [SekretarisController::class, 'notulensiShow'])->name('notulensi.show');
    Route::get('/notulensi/{notulensi}/edit', [SekretarisController::class, 'notulensiEdit'])->name('notulensi.edit');
    Route::put('/notulensi/{notulensi}', [SekretarisController::class, 'notulensiUpdate'])->name('notulensi.update');
    Route::delete('/notulensi/{notulensi}', [SekretarisController::class, 'notulensiDestroy'])->name('notulensi.destroy');
    Route::get('/notulensi/{notulensi}/download', [SekretarisController::class, 'notulensiDownload'])->name('notulensi.download');
    
    // Proker Routes
    Route::get('/proker', [SekretarisController::class, 'prokerIndex'])->name('proker.index');
    Route::get('/proker/create', [SekretarisController::class, 'prokerCreate'])->name('proker.create');
    Route::post('/proker', [SekretarisController::class, 'prokerStore'])->name('proker.store');
    Route::get('/proker/{id}', [SekretarisController::class, 'prokerShow'])->name('proker.show');
    Route::get('/proker/{id}/edit', [SekretarisController::class, 'prokerEdit'])->name('proker.edit');
    Route::put('/proker/{id}', [SekretarisController::class, 'prokerUpdate'])->name('proker.update');
    Route::delete('/proker/{id}', [SekretarisController::class, 'prokerDestroy'])->name('proker.destroy');
    
    // Absen Routes
    Route::get('/absen', [AbsenController::class, 'index'])->name('absen.index');
    Route::post('/absen', [AbsenController::class, 'store'])->name('absen.store');
    Route::post('/absen/bulk', [AbsenController::class, 'storeBulk'])->name('absen.store-bulk');
    Route::put('/absen/{absen}', [AbsenController::class, 'update'])->name('absen.update');
    Route::delete('/absen/{absen}', [AbsenController::class, 'destroy'])->name('absen.destroy');

    // Read-Only Routes untuk melihat fitur koordinator-jurnalistik
    Route::prefix('view')->name('view.')->group(function () {
        // News Routes (Read-Only)
        Route::get('/news', [SekretarisReadOnlyController::class, 'newsIndex'])->name('news.index');
        Route::get('/news/{id}', [SekretarisReadOnlyController::class, 'newsShow'])->name('news.show');

        // Proker Routes (Read-Only)
        Route::get('/prokers', [SekretarisReadOnlyController::class, 'prokersIndex'])->name('prokers.index');
        Route::get('/prokers/{proker}', [SekretarisReadOnlyController::class, 'prokersShow'])->name('prokers.show');

        // Brief Routes (Read-Only)
        Route::get('/briefs', [SekretarisReadOnlyController::class, 'briefsIndex'])->name('briefs.index');
        Route::get('/briefs/{brief}', [SekretarisReadOnlyController::class, 'briefsShow'])->name('briefs.show');

        // Content Routes (Read-Only)
        Route::get('/contents', [SekretarisReadOnlyController::class, 'contentsIndex'])->name('contents.index');
        Route::get('/contents/{content}', [SekretarisReadOnlyController::class, 'contentsShow'])->name('contents.show');

        // Design Routes (Read-Only)
        Route::get('/designs', [SekretarisReadOnlyController::class, 'designsIndex'])->name('designs.index');
        Route::get('/designs/{design}', [SekretarisReadOnlyController::class, 'designsShow'])->name('designs.show');

        // Funfact Routes (Read-Only)
        Route::get('/funfacts', [SekretarisReadOnlyController::class, 'funfactsIndex'])->name('funfacts.index');
        Route::get('/funfacts/{funfact}', [SekretarisReadOnlyController::class, 'funfactsShow'])->name('funfacts.show');

        // User Routes (Read-Only)
        Route::get('/users', [SekretarisReadOnlyController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}', [SekretarisReadOnlyController::class, 'usersShow'])->name('users.show');

        // Brief Humas Routes (Read-Only)
        Route::get('/brief-humas', [SekretarisReadOnlyController::class, 'briefHumasIndex'])->name('brief-humas.index');
        Route::get('/brief-humas/{briefHumas}', [SekretarisReadOnlyController::class, 'briefHumasShow'])->name('brief-humas.show');
    });
});

// Bendahara Routes
Route::prefix('bendahara')->name('bendahara.')->middleware(['auth', 'role:bendahara'])->group(function () {
    Route::get('/dashboard', [BendaharaController::class, 'dashboard'])->name('dashboard');
    
    // Kas Anggota Routes
    Route::get('/kas-anggota', [BendaharaController::class, 'kasAnggotaIndex'])->name('kas-anggota.index');
    Route::get('/kas-anggota/create', [BendaharaController::class, 'kasAnggotaCreate'])->name('kas-anggota.create');
    Route::post('/kas-anggota', [BendaharaController::class, 'kasAnggotaStore'])->name('kas-anggota.store');
    Route::get('/kas-anggota/riwayat', [BendaharaController::class, 'kasAnggotaRiwayat'])->name('kas-anggota.riwayat');
    Route::get('/kas-anggota/{kasAnggota}', [BendaharaController::class, 'kasAnggotaShow'])->name('kas-anggota.show');
    Route::get('/kas-anggota/{kasAnggota}/edit', [BendaharaController::class, 'kasAnggotaEdit'])->name('kas-anggota.edit');
    Route::put('/kas-anggota/{kasAnggota}', [BendaharaController::class, 'kasAnggotaUpdate'])->name('kas-anggota.update');
    Route::delete('/kas-anggota/{kasAnggota}', [BendaharaController::class, 'kasAnggotaDestroy'])->name('kas-anggota.destroy');
    
    // Pemasukan Routes
    Route::get('/pemasukan', [BendaharaController::class, 'pemasukanIndex'])->name('pemasukan.index');
    Route::get('/pemasukan/create', [BendaharaController::class, 'pemasukanCreate'])->name('pemasukan.create');
    Route::post('/pemasukan', [BendaharaController::class, 'pemasukanStore'])->name('pemasukan.store');
    Route::get('/pemasukan/{pemasukan}', [BendaharaController::class, 'pemasukanShow'])->name('pemasukan.show');
    Route::get('/pemasukan/{pemasukan}/edit', [BendaharaController::class, 'pemasukanEdit'])->name('pemasukan.edit');
    Route::put('/pemasukan/{pemasukan}', [BendaharaController::class, 'pemasukanUpdate'])->name('pemasukan.update');
    Route::patch('/pemasukan/{pemasukan}/verify', [BendaharaController::class, 'pemasukanVerify'])->name('pemasukan.verify');
    Route::delete('/pemasukan/{pemasukan}', [BendaharaController::class, 'pemasukanDestroy'])->name('pemasukan.destroy');
    
    // Pengeluaran Routes
    Route::get('/pengeluaran', [BendaharaController::class, 'pengeluaranIndex'])->name('pengeluaran.index');
    Route::get('/pengeluaran/create', [BendaharaController::class, 'pengeluaranCreate'])->name('pengeluaran.create');
    Route::post('/pengeluaran', [BendaharaController::class, 'pengeluaranStore'])->name('pengeluaran.store');
    Route::get('/pengeluaran/{pengeluaran}', [BendaharaController::class, 'pengeluaranShow'])->name('pengeluaran.show');
    Route::get('/pengeluaran/{pengeluaran}/edit', [BendaharaController::class, 'pengeluaranEdit'])->name('pengeluaran.edit');
    Route::put('/pengeluaran/{pengeluaran}', [BendaharaController::class, 'pengeluaranUpdate'])->name('pengeluaran.update');
    Route::patch('/pengeluaran/{pengeluaran}/verify', [BendaharaController::class, 'pengeluaranVerify'])->name('pengeluaran.verify');
    Route::patch('/pengeluaran/{pengeluaran}/approve', [BendaharaController::class, 'pengeluaranApprove'])->name('pengeluaran.approve');
    Route::delete('/pengeluaran/{pengeluaran}', [BendaharaController::class, 'pengeluaranDestroy'])->name('pengeluaran.destroy');
    
    // Laporan Routes
    Route::get('/laporan', [BendaharaController::class, 'laporanKeuangan'])->name('laporan.index');
    Route::get('/laporan/kas-anggota', [BendaharaController::class, 'laporanKasAnggota'])->name('laporan.kas-anggota');
    Route::get('/laporan/export-excel', [BendaharaController::class, 'exportExcel'])->name('laporan.export-excel');
    
    // Kas Settings Routes
    Route::get('/kas-settings', [BendaharaController::class, 'kasSettingsIndex'])->name('kas-settings.index');
    Route::put('/kas-settings', [BendaharaController::class, 'kasSettingsUpdate'])->name('kas-settings.update');
    
    // Read-Only Routes untuk melihat fitur koordinator-jurnalistik
    Route::prefix('view')->name('view.')->group(function () {
        // Dashboard Koordinator Jurnalistik (Read-Only)
        Route::get('/koordinator-jurnalistik/dashboard', [ReadOnlyController::class, 'dashboard'])->name('koordinator-jurnalistik.dashboard');
        
        // News Routes (Read-Only)
        Route::get('/news', [ReadOnlyController::class, 'newsIndex'])->name('news.index');
        Route::get('/news/{id}', [ReadOnlyController::class, 'newsShow'])->name('news.show');
        
        // Proker Routes (Read-Only)
        Route::get('/prokers', [ReadOnlyController::class, 'prokersIndex'])->name('prokers.index');
        Route::get('/prokers/{proker}', [ReadOnlyController::class, 'prokersShow'])->name('prokers.show');
        
        // Brief Routes (Read-Only)
        Route::get('/briefs', [ReadOnlyController::class, 'briefsIndex'])->name('briefs.index');
        Route::get('/briefs/{brief}', [ReadOnlyController::class, 'briefsShow'])->name('briefs.show');
        
        // Content Routes (Read-Only)
        Route::get('/contents', [ReadOnlyController::class, 'contentsIndex'])->name('contents.index');
        Route::get('/contents/{content}', [ReadOnlyController::class, 'contentsShow'])->name('contents.show');
        
        // Design Routes (Read-Only)
        Route::get('/designs', [ReadOnlyController::class, 'designsIndex'])->name('designs.index');
        Route::get('/designs/{design}', [ReadOnlyController::class, 'designsShow'])->name('designs.show');
        
        // Funfact Routes (Read-Only)
        Route::get('/funfacts', [ReadOnlyController::class, 'funfactsIndex'])->name('funfacts.index');
        Route::get('/funfacts/{funfact}', [ReadOnlyController::class, 'funfactsShow'])->name('funfacts.show');
        
        // User Routes (Read-Only)
        Route::get('/users', [ReadOnlyController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}', [ReadOnlyController::class, 'usersShow'])->name('users.show');

        // Brief Humas Routes (Read-Only)
        Route::get('/brief-humas', [ReadOnlyController::class, 'briefHumasIndex'])->name('brief-humas.index');
        Route::get('/brief-humas/{briefHumas}', [ReadOnlyController::class, 'briefHumasShow'])->name('brief-humas.show');

        // Sekretaris: Notulensi & Absen (Read-Only)
        Route::get('/sekretaris/notulensi', [ReadOnlyController::class, 'sekretarisNotulensiIndex'])->name('sekretaris.notulensi.index');
        Route::get('/sekretaris/notulensi/{notulensi}', [ReadOnlyController::class, 'sekretarisNotulensiShow'])->name('sekretaris.notulensi.show');
        Route::get('/sekretaris/notulensi/{notulensi}/download', [ReadOnlyController::class, 'sekretarisNotulensiDownload'])->name('sekretaris.notulensi.download');
        Route::get('/sekretaris/absen', [ReadOnlyController::class, 'sekretarisAbsenIndex'])->name('sekretaris.absen.index');
    });
});

// Public News Routes
Route::prefix('news')->name('news.')->group(function () {
    Route::get('/{news:slug}', [\App\Http\Controllers\Public\NewsController::class, 'show'])->name('show');
});

// Public Listing Routes
Route::get('/kategori/{segment}', [\App\Http\Controllers\Public\ListingController::class, 'category'])->name('public.category');
Route::get('/tipe/{segment}', [\App\Http\Controllers\Public\ListingController::class, 'type'])->name('public.type');
Route::view('/tentang', 'public.about')->name('public.about');

// News Approval Routes - akses: koordinator jurnalistik, koordinator redaksi, anggota redaksi
Route::middleware(['auth', 'role:koordinator_jurnalistik,koordinator_redaksi,anggota_redaksi'])
    ->group(function () {
        Route::post('/news/{id}/approve', [\App\Http\Controllers\NewsApprovalController::class, 'approve'])
            ->name('news.approve');
    });

//
