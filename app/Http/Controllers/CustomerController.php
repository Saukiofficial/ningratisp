<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Admin: Melihat daftar semua pelanggan.
     */
    public function index()
    {
        // Mengambil data customer beserta relasi user dan package
        $customers = Customer::with(['user', 'package'])->latest()->paginate(10);

        return Inertia::render('Admin/Customers/Index', [
            'customers' => $customers
        ]);
    }

    /**
     * Public: Register pelanggan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pelanggan',
            ]);

            // 2. Buat Customer Profile
            Customer::create([
                'user_id' => $user->id,
                'address' => $validated['address'],
                'package_id' => $validated['package_id'],
                'status' => 'active', // Default active atau pending
            ]);
        });

        return redirect()->route('login')->with('message', 'Pendaftaran berhasil! Silakan login.');
    }

    /**
     * Admin: Update status pelanggan.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
            'package_id' => 'exists:packages,id'
        ]);

        $customer->update($validated);

        return redirect()->back()->with('message', 'Data pelanggan diperbarui.');
    }
}
