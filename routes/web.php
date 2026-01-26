<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\EnsureUserHasRole;

// --- CONTROLLERS ---
use App\Http\Controllers\LandingController;
// Controller Admin Lama DIHAPUS karena diganti Filament
// Customer Controllers (Tetap Dipakai)
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ComplaintController as CustomerComplaintController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================================================================

// Halaman Utama
Route::get('/', [LandingController::class, 'index'])->name('home');
// Halaman Paket sudah di-merge ke Home, jadi route '/packages' dihapus

// GROUP: TENTANG KAMI
Route::prefix('tentang-kami')->name('about.')->group(function () {
    Route::get('/', [LandingController::class, 'aboutProfil'])->name('index');
    Route::get('/profil', [LandingController::class, 'aboutProfil'])->name('profil');
    Route::get('/visi-misi', [LandingController::class, 'aboutVisiMisi'])->name('visi-misi');
    Route::get('/topologi', [LandingController::class, 'aboutTopologi'])->name('topologi');
    Route::get('/struktur-organisasi', [LandingController::class, 'aboutStruktur'])->name('struktur');
});

// GROUP: LAYANAN
Route::prefix('layanan')->name('services.')->group(function () {
    Route::get('/', [LandingController::class, 'serviceCloud'])->name('index');
    Route::get('/cloud-access', [LandingController::class, 'serviceCloud'])->name('cloud');
    Route::get('/managed-services', [LandingController::class, 'serviceManaged'])->name('managed');
    Route::get('/consultant', [LandingController::class, 'serviceConsultant'])->name('consultant');
    Route::get('/management', [LandingController::class, 'serviceManagement'])->name('management');
    Route::get('/electrical', [LandingController::class, 'serviceElectrical'])->name('electrical');
    Route::get('/security', [LandingController::class, 'serviceSecurity'])->name('security');
});

// GROUP: TOOLS
Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/speed-test', [LandingController::class, 'toolsSpeedTest'])->name('speed-test');
    Route::get('/tracking', [LandingController::class, 'toolsTracking'])->name('tracking');
    Route::get('/search', [LandingController::class, 'toolsSearch'])->name('search');
});

// Lainnya
Route::get('/coverage-area', [LandingController::class, 'coverage'])->name('landing.coverage');
Route::post('/check-coverage', [LandingController::class, 'checkCoverage'])->name('landing.check-coverage');

// Registrasi (TETAP ADA untuk calon pelanggan)
Route::get('/register', [LandingController::class, 'showRegister'])->name('register');
Route::post('/register', [LandingController::class, 'storeRegister'])->name('register.store');


// =========================================================================
// 2. AUTHENTICATION & BRIDGE
// =========================================================================

require __DIR__.'/auth.php';

// Route Jembatan (Bridge): Mengarahkan user setelah login
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        // UPDATE: Redirect Admin ke Panel Filament
        return redirect('/admin');
    }

    // Customer tetap ke Dashboard React
    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =========================================================================
// 3. ADMIN ROUTES (DIHAPUS - DIGANTIKAN FILAMENT)
// =========================================================================
// Grup route 'admin' manual sebelumnya dihapus karena Filament
// menangani routingnya sendiri secara otomatis.


// =========================================================================
// 4. CUSTOMER PANEL ROUTES (TETAP DIPAKAI)
// =========================================================================
// Halaman dashboard untuk pelanggan (bukan admin) tetap menggunakan React/Inertia
Route::middleware(['auth', 'verified', EnsureUserHasRole::class . ':customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

    // Dashboard Customer
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Pengaduan Customer
    Route::resource('complaints', CustomerComplaintController::class)->only(['index', 'store']);
});
