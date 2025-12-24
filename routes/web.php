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
use App\Http\Controllers\KoordinatorHumas\KoordinatorHumasController as KoordinatorHumasController;
use App\Http\Controllers\KoordinatorHumas\ContentController as KoordinatorHumasContentController;
use App\Http\Controllers\KoordinatorHumas\PenjadwalanController as KoordinatorHumasPenjadwalanController;
use App\Http\Controllers\KoordinatorLitbang\BriefController as KoordinatorLitbangBriefController;
use App\Http\Controllers\Sekretaris\SekretarisController;
use App\Http\Controllers\Sekretaris\AbsenController;
use App\Http\Controllers\Bendahara\BendaharaController;
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
});

// Koordinator Litbang Routes
Route::prefix('koordinator-litbang')->name('koordinator-litbang.')->middleware(['auth', 'role:koordinator_litbang'])->group(function () {
    Route::get('/dashboard', [KoordinatorLitbangController::class, 'dashboard'])->name('dashboard');

    // Brief Routes (sama seperti koordinator jurnalistik)
    Route::resource('briefs', KoordinatorLitbangBriefController::class);

    // Penjadwalan Routes (untuk anggota litbang)
    Route::resource('penjadwalan', KoordinatorLitbangPenjadwalanController::class);
});

// Koordinator Humas Routes
Route::prefix('koordinator-humas')->name('koordinator-humas.')->middleware(['auth', 'role:koordinator_humas'])->group(function () {
    Route::get('/dashboard', [KoordinatorHumasController::class, 'dashboard'])->name('dashboard');
    Route::resource('contents', KoordinatorHumasContentController::class);
    Route::resource('penjadwalan', KoordinatorHumasPenjadwalanController::class);
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
});

// Anggota Litbang Routes
Route::prefix('anggota-litbang')->name('anggota-litbang.')->middleware(['auth', 'role:anggota_litbang'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AnggotaLitbang\AnggotaLitbangController::class, 'dashboard'])->name('dashboard');
    Route::resource('briefs', \App\Http\Controllers\AnggotaLitbang\BriefController::class);
});

// Sekretaris Routes
Route::prefix('sekretaris')->name('sekretaris.')->middleware(['auth', 'role:sekretaris'])->group(function () {
    Route::get('/dashboard', [SekretarisController::class, 'dashboard'])->name('dashboard');
    
    // Notulensi Routes
    Route::get('/notulensi', [SekretarisController::class, 'notulensiIndex'])->name('notulensi.index');
    Route::get('/notulensi/create', [SekretarisController::class, 'notulensiCreate'])->name('notulensi.create');
    Route::post('/notulensi', [SekretarisController::class, 'notulensiStore'])->name('notulensi.store');
    Route::get('/notulensi/{notulensi}', [SekretarisController::class, 'notulensiShow'])->name('notulensi.show');
    Route::get('/notulensi/{notulensi}/edit', [SekretarisController::class, 'notulensiEdit'])->name('notulensi.edit');
    Route::put('/notulensi/{notulensi}', [SekretarisController::class, 'notulensiUpdate'])->name('notulensi.update');
    Route::delete('/notulensi/{notulensi}', [SekretarisController::class, 'notulensiDestroy'])->name('notulensi.destroy');
    
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
    
    // Kas Settings Routes
    Route::get('/kas-settings', [BendaharaController::class, 'kasSettingsIndex'])->name('kas-settings.index');
    Route::put('/kas-settings', [BendaharaController::class, 'kasSettingsUpdate'])->name('kas-settings.update');
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
