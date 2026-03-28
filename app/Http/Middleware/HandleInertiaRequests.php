<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var \App\Models\Customer */
        $user = auth('customers')->user();
        if ($user) {
            $user->setAttribute('is_customer', true);
            $user->setAttribute('whatsapp_number', $user->phone);
            $user = $user?->only('id', 'username', 'email', 'phone', 'full_name', 'latitude', 'longitude', 'address', 'is_customer', 'whatsapp_number');
        }

        return [
            ...parent::share($request),
            // TAMBAHKAN BAGIAN INI AGAR FLASH MESSAGE TERKIRIM
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
            'appEnv' => app()->environment(),
            'auth.user' => $user,
        ];
    }
}
