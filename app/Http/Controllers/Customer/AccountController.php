<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                'username' => $customer->username,
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:32'],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $customer->update([
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
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated.');
    }
}
