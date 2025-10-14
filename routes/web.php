<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KoordinatorJurnalistikController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\BriefController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\TempImageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news/{news}', [HomeController::class, 'show'])->name('news.show');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Always show debug info for login attempts
    session()->flash('login_attempt', [
        'email' => $request->email,
        'timestamp' => now()->format('Y-m-d H:i:s')
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        // Debug: Show user role information
        session()->flash('debug_info', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'isKoordinatorJurnalistik' => $user->isKoordinatorJurnalistik(),
            'isBendahara' => $user->isBendahara(),
            'all_roles' => [
                'ROLE_KOORDINATOR_JURNALISTIK' => \App\Models\User::ROLE_KOORDINATOR_JURNALISTIK,
                'ROLE_BENDAHARA' => \App\Models\User::ROLE_BENDAHARA,
            ]
        ]);

        if ($user->isKoordinatorJurnalistik()) {
            return redirect('/koordinator-jurnalistik/dashboard')->with('success', 'Login berhasil sebagai Koordinator Jurnalistik');
        } elseif ($user->isBendahara()) {
            return redirect('/bendahara/dashboard')->with('success', 'Login berhasil sebagai Bendahara');
        }

        // If no specific role matches, redirect to home with debug info
        return redirect('/')->with('login_debug', 'User role: ' . $user->role . ' - No specific dashboard found');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email')->with('login_failed', 'Login gagal - kredensial tidak cocok');
})->name('login.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Koordinator Jurnalistik routes (previously admin routes)
Route::prefix('koordinator-jurnalistik')->name('koordinator-jurnalistik.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [KoordinatorJurnalistikController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::resource('users', UserController::class);
    
    // News management - using KoordinatorJurnalistikController methods
    Route::get('/news', [KoordinatorJurnalistikController::class, 'newsIndex'])->name('news.index');
    Route::get('/news/create', [KoordinatorJurnalistikController::class, 'newsCreate'])->name('news.create');
    Route::post('/news', [KoordinatorJurnalistikController::class, 'newsStore'])->name('news.store');
    Route::get('/news/{id}', [KoordinatorJurnalistikController::class, 'newsShow'])->name('news.show');
    Route::get('/news/{id}/edit', [KoordinatorJurnalistikController::class, 'newsEdit'])->name('news.edit');
    Route::put('/news/{id}', [KoordinatorJurnalistikController::class, 'newsUpdate'])->name('news.update');
    Route::delete('/news/{id}', [KoordinatorJurnalistikController::class, 'newsDestroy'])->name('news.destroy');
    Route::post('/news/upload-image', [KoordinatorJurnalistikController::class, 'uploadImage'])->name('news.upload-image');
    
    // Temp images route for cropping functionality
    Route::post('/temp-images', [TempImageController::class, 'store'])->name('temp-images.store');
    
    // Comment management
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Proker management
    Route::resource('prokers', ProkerController::class);
    
    // Brief management
    Route::resource('briefs', BriefController::class);
    
    // Content management
    Route::resource('contents', ContentController::class);
    
    // Design management
    Route::resource('designs', DesignController::class);
});

// Bendahara routes
Route::prefix('bendahara')->name('bendahara.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [BendaharaController::class, 'dashboard'])->name('dashboard');
    
    // Kas Anggota management
    Route::prefix('kas-anggota')->name('kas-anggota.')->group(function () {
        Route::get('/', [BendaharaController::class, 'kasAnggotaIndex'])->name('index');
        Route::get('/create', [BendaharaController::class, 'kasAnggotaCreate'])->name('create');
        Route::post('/', [BendaharaController::class, 'kasAnggotaStore'])->name('store');
        Route::get('/{kasAnggota}', [BendaharaController::class, 'kasAnggotaShow'])->name('show');
        Route::get('/{kasAnggota}/edit', [BendaharaController::class, 'kasAnggotaEdit'])->name('edit');
        Route::put('/{kasAnggota}', [BendaharaController::class, 'kasAnggotaUpdate'])->name('update');
        Route::delete('/{kasAnggota}', [BendaharaController::class, 'kasAnggotaDestroy'])->name('destroy');
    });
    
    // Pemasukan management
    Route::prefix('pemasukan')->name('pemasukan.')->group(function () {
        Route::get('/', [BendaharaController::class, 'pemasukanIndex'])->name('index');
        Route::get('/create', [BendaharaController::class, 'pemasukanCreate'])->name('create');
        Route::post('/', [BendaharaController::class, 'pemasukanStore'])->name('store');
        Route::get('/{pemasukan}', [BendaharaController::class, 'pemasukanShow'])->name('show');
        Route::get('/{pemasukan}/edit', [BendaharaController::class, 'pemasukanEdit'])->name('edit');
        Route::put('/{pemasukan}', [BendaharaController::class, 'pemasukanUpdate'])->name('update');
        Route::delete('/{pemasukan}', [BendaharaController::class, 'pemasukanDestroy'])->name('destroy');
        Route::patch('/{pemasukan}/verify', [BendaharaController::class, 'pemasukanVerify'])->name('verify');
    });
    
    // Pengeluaran management
    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [BendaharaController::class, 'pengeluaranIndex'])->name('index');
        Route::get('/create', [BendaharaController::class, 'pengeluaranCreate'])->name('create');
        Route::post('/', [BendaharaController::class, 'pengeluaranStore'])->name('store');
        Route::get('/{pengeluaran}', [BendaharaController::class, 'pengeluaranShow'])->name('show');
        Route::get('/{pengeluaran}/edit', [BendaharaController::class, 'pengeluaranEdit'])->name('edit');
        Route::put('/{pengeluaran}', [BendaharaController::class, 'pengeluaranUpdate'])->name('update');
        Route::delete('/{pengeluaran}', [BendaharaController::class, 'pengeluaranDestroy'])->name('destroy');
        Route::patch('/{pengeluaran}/approve', [BendaharaController::class, 'pengeluaranApprove'])->name('approve');
        Route::patch('/{pengeluaran}/pay', [BendaharaController::class, 'pengeluaranPay'])->name('pay');
    });
    
    // Kas Settings management
    Route::prefix('kas-settings')->name('kas-settings.')->group(function () {
        Route::get('/', [BendaharaController::class, 'kasSettingsIndex'])->name('index');
        Route::put('/', [BendaharaController::class, 'kasSettingsUpdate'])->name('update');
    });
    
    // Laporan keuangan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/keuangan', [BendaharaController::class, 'laporanKeuangan'])->name('keuangan');
        Route::get('/kas-anggota', [BendaharaController::class, 'laporanKasAnggota'])->name('kas-anggota');
        Route::get('/export-excel', [BendaharaController::class, 'exportExcel'])->name('export-excel');
    });
});
