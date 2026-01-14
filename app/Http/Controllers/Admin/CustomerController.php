<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        // Ambil user dengan role customer
        $users = User::where('role', 'customer')->with('package')->latest()->get();
        $packages = Package::all();

        return Inertia::render('Admin/Customers', [
            'users' => $users,
            'packages' => $packages
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended,inactive',
            'package_id' => 'required|exists:packages,id'
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Status pelanggan diperbarui');
    }
}
