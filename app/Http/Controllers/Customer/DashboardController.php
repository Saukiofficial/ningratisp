<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User; // Import Model User
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Sekarang Intelephense tahu $user adalah User model yang punya method load()
        $user->load('package');

        $subscriptionData = [
            'package_name' => $user->package ? $user->package->name : 'Tidak ada paket',
            'speed' => $user->package ? $user->package->speed : '-',
            'price' => $user->package ? $user->package->price : 0,
            'status' => $user->status,
        ];

        return Inertia::render('Customer/Dashboard', [
            'subscription' => $subscriptionData
        ]);
    }
}
