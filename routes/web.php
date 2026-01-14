<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// Import Middleware secara eksplisit agar aman jika alias 'role' belum terdaftar di Kernel
use App\Http\Middleware\EnsureUserHasRole;

// Controllers
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ComplaintController as CustomerComplaintController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Public / Landing Page Routes ---

// Halaman Utama
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/packages', [LandingController::class, 'packages'])->name('landing.packages');

// GROUP TENTANG KAMI
// URL: /tentang-kami/profil, /tentang-kami/visi-misi, dst.
Route::prefix('tentang-kami')->name('about.')->group(function () {
    Route::get('/', [LandingController::class, 'aboutProfil'])->name('index'); // Default ke profil
    Route::get('/profil', [LandingController::class, 'aboutProfil'])->name('profil');
    Route::get('/visi-misi', [LandingController::class, 'aboutVisiMisi'])->name('visi-misi');
    Route::get('/topologi', [LandingController::class, 'aboutTopologi'])->name('topologi');
    Route::get('/struktur-organisasi', [LandingController::class, 'aboutStruktur'])->name('struktur');
});

// GROUP LAYANAN
// URL: /layanan/cloud-access, /layanan/managed-services, dst.
Route::prefix('layanan')->name('services.')->group(function () {
    Route::get('/', [LandingController::class, 'serviceCloud'])->name('index'); // Default ke cloud
    Route::get('/cloud-access', [LandingController::class, 'serviceCloud'])->name('cloud');
    Route::get('/managed-services', [LandingController::class, 'serviceManaged'])->name('managed');
    Route::get('/consultant', [LandingController::class, 'serviceConsultant'])->name('consultant');
    Route::get('/management', [LandingController::class, 'serviceManagement'])->name('management');
    Route::get('/electrical', [LandingController::class, 'serviceElectrical'])->name('electrical');
    Route::get('/security', [LandingController::class, 'serviceSecurity'])->name('security');
});

// GROUP TOOLS
// URL: /tools/speed-test, /tools/tracking
Route::prefix('tools')->name('tools.')->group(function () {
    // Speed Test Native
    Route::get('/speed-test', [LandingController::class, 'toolsSpeedTest'])->name('speed-test');

    // Tracking Status Pelanggan (Pengganti Search Biasa)
    Route::get('/tracking', [LandingController::class, 'toolsTracking'])->name('tracking');

    // Search Umum (Opsional/Fallback)
    Route::get('/search', [LandingController::class, 'toolsSearch'])->name('search');
});

// Halaman Coverage Area
Route::get('/coverage-area', [LandingController::class, 'coverage'])->name('landing.coverage');

// Registrasi
Route::get('/register', [LandingController::class, 'showRegister'])->name('register');
Route::post('/register', [LandingController::class, 'storeRegister'])->name('register.store');


// --- 2. Bridge Route (Dashboard) ---
// Mengarahkan user ke dashboard yang benar sesuai role setelah login
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 3. Authentication Routes ---
// Breeze auth routes (Login, Logout, Password Reset, etc.)
require __DIR__.'/auth.php';


// --- 4. Admin Routes ---
// Middleware 'auth' untuk cek login, dan EnsureUserHasRole::class . ':admin' untuk cek hak akses admin
Route::middleware(['auth', 'verified', EnsureUserHasRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Paket (CRUD Resource)
    Route::resource('packages', PackageController::class);

    // Manajemen Pelanggan
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::put('/customers/{user}', [CustomerController::class, 'update'])->name('customers.update');

    // Manajemen Pengaduan
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::put('/complaints/{complaint}/resolve', [AdminComplaintController::class, 'resolve'])->name('complaints.resolve');
});


// --- 5. Customer Routes ---
// Middleware khusus customer
Route::middleware(['auth', 'verified', EnsureUserHasRole::class . ':customer'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard Customer
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Pengaduan Customer (Hanya Index & Store)
    Route::resource('complaints', CustomerComplaintController::class)->only(['index', 'store']);
});
