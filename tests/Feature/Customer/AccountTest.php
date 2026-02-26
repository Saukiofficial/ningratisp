<?php

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays the customer account page', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer, 'customers')
        ->get('/customer/account');

    $response->assertOk();
});

it('updates customer account information', function () {
    $customer = Customer::factory()->create([
        'email' => 'old@example.com',
        'phone' => '620000000000',
        'address' => 'Old Address',
    ]);

    $response = $this
        ->actingAs($customer, 'customers')
        ->from('/customer/account')
        ->put('/customer/account', [
            'email' => 'new@example.com',
            'whatsapp_number' => '6281234567890',
            'address' => 'New Address',
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/customer/account');

    $customer->refresh();

    expect($customer->email)->toBe('new@example.com')
        ->and($customer->phone)->toBe('6281234567890')
        ->and($customer->address)->toBe('New Address')
        ->and((float) $customer->latitude)->toBe(-6.2)
        ->and((float) $customer->longitude)->toBe(106.8166667);
});

it('updates customer password', function () {
    $customer = Customer::factory()->create([
        'password' => bcrypt('secret123'),
    ]);

    $response = $this
        ->actingAs($customer, 'customers')
        ->from('/customer/account')
        ->put('/customer/account/password', [
            'current_password' => 'secret123',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/customer/account');

    $this->assertCredentials(
        [
            'username' => $customer->username,
            'password' => 'new-password-123',
        ],
        'customers'
    );
});
