<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Package;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung statistik sederhana
        $activeUsers = User::where('role', 'customer')->where('status', 'active')->count();
        $pendingComplaints = Complaint::where('status', 'pending')->count();

        // Hitung estimasi revenue (User Aktif * Harga Paketnya)
        // Ini butuh relasi user->package di Model User
        $users = User::where('status', 'active')->with('package')->get();
        $revenue = $users->sum(function($user) {
            return $user->package ? $user->package->price : 0;
        });

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'activeUsers' => $activeUsers,
                'pendingComplaints' => $pendingComplaints,
                'revenue' => $revenue
            ],
            'recentActivity' => [] // Bisa diisi data log nanti
        ]);
    }
}
