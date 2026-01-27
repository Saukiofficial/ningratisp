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
use App\Helpers\MikrotikAPI;

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


Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/service-details', function () {
    return view('service-details');
})->name('service-details');

Route::group(['prefix' => 'midtrans/payment'], function () {
    Route::get('success', fn() => view('temp.thank-page'));
    Route::get('failed', fn() => view('temp.failed-page'));
});
Route::get('check-voucher', fn() => view('temp.voucher-check'));

require __DIR__ . '/customer.php';
