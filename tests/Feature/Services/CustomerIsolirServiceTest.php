<?php

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Invoices;
use App\Models\Payment;
use App\Services\CustomerIsolirService;
use App\Models\PppProfile;
use App\Models\Packages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;

uses(RefreshDatabase::class);

beforeEach(function () {
    $profile = PppProfile::factory()->create();
    Packages::factory()->create(['ppp_profile_id' => $profile->id]);
});

it('identifies sync tasks correctly', function () {
    $mikrotikApi = $this->mock(MikrotikAPINative::class);
    
    // Mock MikroTik secrets
    $mikrotikApi->shouldReceive('getPppSecrets')
        ->once()
        ->andReturn([
            ['name' => 'user_expired', 'comment' => 'expired - 2026-06-17', 'last-logged-out' => '2026-06-17 10:00:00'],
            ['name' => 'user_active', 'comment' => 'lunas'],
        ]);
        
    // Create local customers
    $customerExpired = Customer::factory()->create([
        'username' => 'user_expired',
        'isolir_at' => null, // Should be set to isolir
    ]);
    
    $customerToClear = Customer::factory()->create([
        'username' => 'user_active',
        'isolir_at' => now(), // Should be cleared
    ]);
    
    $service = new CustomerIsolirService($mikrotikApi);
    $tasks = $service->getSyncTasks();
    
    expect($tasks)->toHaveCount(2);
    
    $setTask = collect($tasks)->firstWhere('action', 'set_isolir');
    expect($setTask['username'])->toBe('user_expired');
    
    $clearTask = collect($tasks)->firstWhere('action', 'clear_isolir');
    expect($clearTask['username'])->toBe('user_active');
});

it('processes sync tasks correctly', function () {
    $mikrotikApi = $this->mock(MikrotikAPINative::class);
    $customer = Customer::factory()->create(['isolir_at' => null]);
    
    $service = new CustomerIsolirService($mikrotikApi);
    
    $task = [
        'customer_id' => $customer->id,
        'action' => 'set_isolir',
        'comment' => 'expired test',
        'last_logged_out' => '2026-06-17 12:00:00'
    ];
    
    $service->processSyncTask($task);
    
    $customer->refresh();
    expect($customer->isolir_at)->not->toBeNull();
    expect($customer->comment)->toBe('expired test');
});

it('identifies open tasks correctly', function () {
    $mikrotikApi = $this->mock(MikrotikAPINative::class);
    $date = now();
    $month = strtolower($date->format('M'));
    
    $mikrotikApi->shouldReceive('getPppSecrets')
        ->once()
        ->andReturn([
            ['name' => 'paid_user', 'comment' => "expired - $month", '.id' => '*1'],
        ]);
        
    $customer = Customer::factory()->create(['username' => 'paid_user']);
    $customerPackage = $customer->latestCustomerPackage;

    $invoice = Invoices::forceCreate([
        'customer_package_id' => $customerPackage->id,
        'invoice_number' => 'INV-001',
        'payment_status' => Payment::STATUS_PAID,
        'amount' => 100000,
        'total_amount' => 100000,
        'paid_amount' => 100000,
        'balance_due' => 0,
        'invoice_date' => $date,
        'due_date' => $date,
        'period_start' => $date,
        'period_end' => $date,
        'status' => Invoices::STATUS_PAID,
    ]);
    $payment = Payment::forceCreate([
        'payment_datetime' => $date,
        'total_amount' => 100000,
        'payment_method_id' => 1,
        'payment_type' => 'incoming',
        'reference_id' => 'REF-001',
    ]);
    
    // Link payment to invoice via allocation or pivot
    $invoice->payments()->attach($payment->id, ['amount' => 100000, 'allocated_at' => now()]);
    
    $service = new CustomerIsolirService($mikrotikApi);
    $tasks = $service->getOpenTasks();
    
    expect($tasks)->toHaveCount(1);
    expect($tasks[0]['username'])->toBe('paid_user');
});
