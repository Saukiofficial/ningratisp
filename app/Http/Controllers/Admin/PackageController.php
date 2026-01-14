<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Packages', [
            'packages' => Package::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'speed' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
        ]);

        Package::create($validated);

        return redirect()->back()->with('success', 'Paket berhasil ditambahkan');
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'speed' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $package->update($validated);

        return redirect()->back()->with('success', 'Paket berhasil diupdate');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->back()->with('success', 'Paket dihapus');
    }
}
