<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class LandingController extends Controller
{
    // --- MAIN PAGES ---

    public function index()
    {
        // Mengambil data paket untuk ditampilkan di section 'Pilihan Paket' di Homepage
        $packages = Package::all();
        return Inertia::render('Landing/Home', ['packages' => $packages]);
    }

    public function packages()
    {
        $packages = Package::all();
        return Inertia::render('Landing/Packages', ['packages' => $packages]);
    }

    // --- TENTANG KAMI PAGES ---

    public function aboutProfil() { return Inertia::render('Landing/About/Profil'); }
    public function aboutVisiMisi() { return Inertia::render('Landing/About/VisiMisi'); }
    public function aboutTopologi() { return Inertia::render('Landing/About/Topologi'); }
    public function aboutStruktur() { return Inertia::render('Landing/About/Struktur'); }

    // --- LAYANAN PAGES ---

    public function serviceCloud() { return Inertia::render('Landing/Services/Cloud'); }
    public function serviceManaged() { return Inertia::render('Landing/Services/Managed'); }
    public function serviceConsultant() { return Inertia::render('Landing/Services/Consultant'); }
    public function serviceManagement() { return Inertia::render('Landing/Services/Management'); }
    public function serviceElectrical() { return Inertia::render('Landing/Services/Electrical'); }
    public function serviceSecurity() { return Inertia::render('Landing/Services/Security'); }

    // --- TOOLS PAGES ---

    public function toolsSpeedTest()
    {
        // Mengarahkan ke file SpeedTestNative.jsx yang ada di folder Landing/Tools
        return Inertia::render('Landing/Tools/SpeedTestNative');
    }

    public function toolsTracking(Request $request)
    {
        $trackResult = null;

        // Logika Pencarian Pelanggan
        // Jika ada parameter 'query' di URL
        if ($request->has('query') && $request->query('query') != '') {
            $search = $request->query('query');

            // Cari user berdasarkan ID atau Email
            // Pastikan relasi 'package' dimuat (eager loading)
            $user = User::with('package')
                        ->where('id', $search)
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->first();

            if ($user) {
                // Return data spesifik untuk keamanan (jangan return password dll)
                $trackResult = [
                    'id' => $user->id,
                    'name' => $user->name,
                    // Masking email untuk privasi (opsional), misal: b***@gmail.com
                    'email' => $user->email,
                    'address' => $user->address,
                    'status' => $user->status,
                    'package' => $user->package
                ];
            } else {
                // Jika tidak ketemu, kirim flash message error via session props Inertia
                // Atau bisa di-handle di frontend dengan mengecek null result
                return Inertia::render('Landing/Tools/Tracking', [
                    'trackResult' => null,
                    'flash' => [
                        'error' => 'Pelanggan dengan ID atau Email tersebut tidak ditemukan.'
                    ]
                ]);
            }
        }

        return Inertia::render('Landing/Tools/Tracking', [
            'trackResult' => $trackResult,
            // Flash message standar Laravel (jika ada)
            'flash' => session('flash') ?? []
        ]);
    }

    // Fitur Search Umum (Opsional, jika Anda ingin memisahkan tracking dan search konten)
    // Saat ini di Navbar diarahkan ke Tracking, jadi method ini bisa jadi fallback/future use
    public function toolsSearch()
    {
        return Inertia::render('Landing/Tools/Search');
    }

    // --- EXTRAS ---

    public function coverage()
    {
        // Jika file Coverage.jsx belum dibuat, redirect ke Home
        // return Inertia::render('Landing/Coverage');
        return redirect()->route('home');
    }

    // --- AUTH / REGISTRATION ---

    public function showRegister(Request $request)
    {
        return Inertia::render('Landing/Register', [
            'selectedPackage' => $request->query('package')
        ]);
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'address' => $request->address,
            'package_id' => $request->package_id,
            'status' => 'active', // Default aktif setelah daftar
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect(route('customer.dashboard'));
    }
}
