<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    public function edit(Request $request): Response
    {
        $customer = $request->user();

        return Inertia::render('Customer/Account', [
            'customer' => [
                'email' => $customer->email,
                'whatsapp_number' => $customer->phone,
                'address' => $customer->address,
                'latitude' => $customer->latitude,
                'longitude' => $customer->longitude,
                'full_name' => $customer->full_name,
                'billing_number' => $customer->billing_number
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\Customer */
        $customer = $request->user();

        $validated = $request->validate([
            'full_name' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:32'],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $customer->offsetUnset('is_customer');
        $customer->offsetUnset('whatsapp_number');

        $customer->update([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['whatsapp_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        return back()->with('success', 'Account information updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:customers'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Password updated.');
    }
}
