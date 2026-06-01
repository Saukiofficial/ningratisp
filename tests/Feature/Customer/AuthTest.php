<?php

use App\Models\Customer;
use App\Models\Packages;
use App\Models\PppProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    PppProfile::factory()->create();
    Packages::factory()->create();
});

it('can logout a customer', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customers');

    $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->post('/customer/logout');

    $response->assertRedirect(route('login'));
    $this->assertGuest('customers');
});

it('can login a customer', function () {
    $customer = Customer::factory()->create([
        'username' => 'testcustomer',
        'password' => 'password123',
    ]);

    $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->post('/customer/login', [
            'username' => 'testcustomer',
            'password' => 'password123',
        ]);

    $response->assertRedirect(route('customer.dashboard'));
    $this->assertAuthenticatedAs($customer, 'customers');
});

it('keeps other guards logged in when customer logs out', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    // Login both
    $this->actingAs($user, 'web');
    $this->actingAs($customer, 'customers');

    $this->assertAuthenticated('web');
    $this->assertAuthenticated('customers');

    // Logout customer
    $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->post('/customer/logout');

    $response->assertRedirect(route('login'));
    
    $this->assertGuest('customers');
    $this->assertAuthenticated('web');
});
