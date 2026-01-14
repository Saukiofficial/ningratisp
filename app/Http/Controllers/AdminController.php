<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Dashboard Admin: Menampilkan statistik ringkas.
     */
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_customers' => Customer::count(),
                'active_packages' => Package::where('status', 'active')->count(),
                'open_complaints' => Complaint::where('status', 'open')->count(),
                'recent_complaints' => Complaint::with('customer.user')
                                        ->latest()
                                        ->take(5)
                                        ->get(),
            ]
        ]);
    }
}
