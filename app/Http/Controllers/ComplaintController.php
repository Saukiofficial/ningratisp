<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ComplaintController extends Controller
{
    /**
     * Menampilkan komplain.
     * Jika Admin: Lihat semua.
     * Jika Pelanggan: Lihat punya sendiri.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Complaint::with(['customer.user']);

        if ($user->role === 'pelanggan') {
            // Filter hanya komplain milik user yang login
            $query->whereHas('customer', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Admin melihat semua (tidak perlu filter user)

        return Inertia::render('Complaints/Index', [
            'complaints' => $query->latest()->paginate(10),
            'userRole' => $user->role // Dikirim ke frontend untuk kondisi tampilan
        ]);
    }

    /**
     * Pelanggan: Buat komplain baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Ambil customer profile dari user yang login
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->back()->withErrors(['message' => 'Profile pelanggan tidak ditemukan.']);
        }

        Complaint::create([
            'customer_id' => $customer->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        return redirect()->back()->with('message', 'Laporan berhasil dikirim.');
    }

    /**
     * Admin/Teknisi: Update status komplain.
     */
    public function update(Request $request, Complaint $complaint)
    {
        // Validasi akses (opsional jika sudah di middleware route)
        $user = Auth::user();

        // PERBAIKAN: Menggunakan in_array langsung pada property 'role'
        // Ini menghindari error "Undefined method 'hasRole'" jika method tersebut tidak terdeteksi
        if (!in_array($user->role, ['admin', 'teknisi', 'cs'])) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $complaint->update($validated);

        return redirect()->back()->with('message', 'Status komplain diperbarui.');
    }
}
