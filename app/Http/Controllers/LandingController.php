<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use App\Models\CoverageArea; // Pastikan Model ini ada atau dibuat
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class LandingController extends Controller
{
    // --- MAIN ---
    public function index()
    {
        $packages = Package::all();
        return Inertia::render('Landing/Home', ['packages' => $packages]);
    }

    public function packages()
    {
        $packages = Package::all();
        return Inertia::render('Landing/Packages', ['packages' => $packages]);
    }

    // --- CEK COVERAGE METHOD ---
    public function checkCoverage(Request $request)
    {
        $request->validate(['area' => 'required|string|min:3']);

        // Bersihkan input user (lowercase, trim)
        $query = strtolower(trim($request->area));

        // --- UPDATE: MENGGUNAKAN DATABASE ---
        // Mencari apakah ada area di database yang cocok dengan input user
        // Asumsi tabel 'coverage_areas' memiliki kolom 'name' atau 'kelurahan'/'kecamatan'
        // Jika model CoverageArea belum ada, kode ini akan error.
        // Pastikan Anda membuat modelnya: php artisan make:model CoverageArea -m

        $isCovered = false;

        // Cek keberadaan Model CoverageArea untuk menghindari error jika belum dibuat
        if (class_exists(CoverageArea::class)) {
            $isCovered = CoverageArea::where('name', 'LIKE', "%{$query}%")
                        ->orWhere('district', 'LIKE', "%{$query}%") // Opsional: cari berdasarkan kecamatan
                        ->exists();
        } else {
            // FALLBACK JIKA BELUM ADA DATABASE: (Sementara kosong atau logika lain)
            // Untuk saat ini kita anggap false jika tabel belum siap
            $isCovered = false;
        }

        if ($isCovered) {
            return redirect()->back()->with('success', "Selamat! Area '$request->area' TERJANGKAU oleh layanan NingratNet. Silakan daftar sekarang!");
        } else {
            return redirect()->back()->with('error', "Maaf, area '$request->area' belum terjangkau saat ini. Silakan hubungi CS kami.");
        }
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
        return Inertia::render('Landing/Tools/SpeedTest');
    }

    public function toolsTracking(Request $request)
    {
        $trackResult = null;
        if ($request->has('query') && $request->query('query') != '') {
            $search = $request->query('query');
            $user = User::with('package')->where('id', $search)->orWhere('email', 'LIKE', "%{$search}%")->first();

            if ($user) {
                $trackResult = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'address' => $user->address,
                    'status' => $user->status,
                    'package' => $user->package
                ];
            } else {
                return Inertia::render('Landing/Tools/Tracking', [
                    'trackResult' => null,
                    'flash' => ['error' => 'Pelanggan dengan ID atau Email tersebut tidak ditemukan.']
                ]);
            }
        }
        return Inertia::render('Landing/Tools/Tracking', ['trackResult' => $trackResult, 'flash' => session('flash') ?? []]);
    }

    public function toolsSearch()
    {
        return Inertia::render('Landing/Tools/Search');
    }

    // --- EXTRAS ---
    public function coverage() { return redirect()->route('home'); }

    // --- AUTH / REGISTRATION ---
    public function showRegister(Request $request)
    {
        return Inertia::render('Landing/Register', ['selectedPackage' => $request->query('package')]);
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
            'status' => 'active',
        ]);

        event(new Registered($user));
        auth()->login($user);
        return redirect(route('customer.dashboard'));
    }
}
