<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{

    const GUARD = 'customers';

    /**
     * Display the login page.
     */
    public function index(): RedirectResponse|Response
    {
        if (Auth::guard(self::GUARD)->check()) {
            return to_route('customer.dashboard');
        }

        return Inertia::render('Customer/Login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @throws ValidationException
     */
    public function authProcess(AuthRequest $request): RedirectResponse
    {
        $customer = Customer::query()
            ->where('username', $request->username)
            ->orWhere('billing_number', $request->username)
            ->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        Auth::guard(self::GUARD)->login($customer, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->route('customer.dashboard');
    }

    /**
     * Log the user out of the application.
     */
    public function logOut(Request $request): RedirectResponse
    {
        Auth::guard(self::GUARD)->logout();

        return redirect()->route('login');
    }
}
