<?php

use App\Models\Customer;
use App\Models\CustomerPackages;
use App\Models\Discount;
use App\Models\Fee;
use App\Models\Invoices;
use App\Models\Packages;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\DiscountService;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('can generate monthly invoices for active customer packages', function () {
    // Arrange
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => 100000, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();

    $invoiceService = new InvoiceService(new DiscountService());

    // Act
    $generatedCount = $invoiceService->generateMonthlyInvoicesForDate(Carbon::now());

    // Assert
    expect($generatedCount)->toBe(1);
    $this->assertDatabaseHas('invoices', [
        'customer_package_id' => 1,
        'invoice_type' => Invoices::TYPE_MONTHLY,
        'status' => Invoices::STATUS_UNPAID,
        'amount' => 100000,
    ]);
});

it('does not generate duplicate monthly invoices for the same period', function () {
    // Arrange
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => 100000, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();

    $invoiceService = new InvoiceService(new DiscountService());
    $invoiceService->generateMonthlyInvoicesForDate(Carbon::now());

    // Act
    $generatedCount = $invoiceService->generateMonthlyInvoicesForDate(Carbon::now());

    // Assert
    expect($generatedCount)->toBe(0);
    $this->assertDatabaseCount('invoices', 1);
});

it('does not generate invoices for suspended customer packages', function () {
    // Arrange
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => 100000, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();
    $customer->customerPackages()->first()->update(['status' => CustomerPackages::STATUS_SUSPENDED]);

    $invoiceService = new InvoiceService(new DiscountService());

    // Act
    $generatedCount = $invoiceService->generateMonthlyInvoicesForDate(Carbon::now());

    // Assert
    expect($generatedCount)->toBe(0);
    $this->assertDatabaseCount('invoices', 0);
});

it('can generate a single manual invoice', function () {
    // Arrange
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => 150000, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();
    $customerPackage = $customer->customerPackages()->first();

    $invoiceService = new InvoiceService(new DiscountService());
    $dueDate = Carbon::now()->addDays(15)->toDateString();

    // Act
    $invoiceService->createManualInvoicesForPackage($customerPackage->id, $dueDate);

    // Assert
    $this->assertDatabaseHas('invoices', [
        'customer_package_id' => $customerPackage->id,
        'invoice_type' => Invoices::TYPE_MANUAL,
        'status' => Invoices::STATUS_UNPAID,
        'amount' => 150000,
        'due_date' => $dueDate,
    ]);
});

it('can generate multiple manual invoices in a batch', function () {
    // Arrange
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => 120000, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();
    $customerPackage = $customer->customerPackages()->first();

    $invoiceService = new InvoiceService(new DiscountService());
    $dueDate = Carbon::now()->addDays(15)->toDateString();
    $batch = 3;

    // Act
    $invoiceService->createManualInvoicesForPackage($customerPackage->id, $dueDate, $batch);

    // Assert
    $this->assertDatabaseCount('invoices', $batch);
    for ($i = 0; $i < $batch; $i++) {
        $expectedDueDate = Carbon::parse($dueDate)->addMonths($i)->toDateString();
        $this->assertDatabaseHas('invoices', [
            'customer_package_id' => $customerPackage->id,
            'invoice_type' => Invoices::TYPE_MANUAL,
            'status' => Invoices::STATUS_UNPAID,
            'amount' => 120000,
            'due_date' => $expectedDueDate,
        ]);
    }
});

it('applies discounts to monthly invoices', function () {
    // Arrange
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => 200000, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();
    $customerPackage = $customer->customerPackages()->first();
    $discount = Discount::factory()->create(['value' => 10, 'type' => 'percentage']);
    $customer->discounts()->attach($discount->id);

    $discountServiceMock = Mockery::mock(DiscountService::class);
    $discountServiceMock->shouldReceive('calculateInvoiceDiscount')->once();

    $invoiceService = new InvoiceService($discountServiceMock);

    // Act
    $invoiceService->generateMonthlyInvoicesForDate(Carbon::now());

    // Assert
    $this->assertDatabaseHas('invoices', [
        'customer_package_id' => $customerPackage->id,
        'amount' => 200000,
    ]);
});

it('recalculates invoice totals correctly', function () {
    // Arrange
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => 100000, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();
    $customerPackage = $customer->customerPackages()->first();

    $invoiceService = new InvoiceService(new DiscountService());
    $invoice = $invoiceService->generateInvoiceForCustomerPackage(
        $customerPackage,
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth(),
        Carbon::now()
    );

    // Act
    $fee = 25000;
    $invoiceService->addItem($invoice, 'charge', 'Additional Fee', 1, $fee);
    $invoice->recalculateTotals();

    // Assert
    $totalAmount = floatval($package->price) + floatval($fee);
    expect(floatval($invoice->total_amount))->toBe($totalAmount);
    expect(floatval($invoice->amount))->toBe(floatval($package->price));
});

it('adjusts invoice amount if paid within the previous billing cycle', function () {
    // Arrange
    $paymentMethod = PaymentMethod::create([
        'name' => 'Test Method',
        'code' => 'test',
        'category' => PaymentMethod::BANK,
        'is_active' => true,
    ]);
    $fee = $paymentMethod->fee()->create([
        'amount' => 2500,
        'unit' => Fee::NOMINAL,
        'started_at' => now()
    ]);

    $price = 100000;
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => $price, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();
    $customerPackage = $customer->customerPackages()->first();

    // Create a previous invoice for the customer
    $previousInvoiceDate = Carbon::now()->subMonth();
    $previousInvoice = new Invoices([
        'customer_package_id' => $customerPackage->id,
        'customer_id' => $customer->id,
        'invoice_date' => $previousInvoiceDate->toDateString(),
        'due_date' => $previousInvoiceDate->copy()->addDays(30)->toDateString(),
        'period_start' => $previousInvoiceDate->copy()->startOfMonth()->toDateString(),
        'period_end' => $previousInvoiceDate->copy()->endOfMonth()->toDateString(),
        'status' => Invoices::STATUS_PAID,
        'amount' => $price,
        'invoice_number' => 'INV-TEST-PRORATE',
        'invoice_type' => Invoices::TYPE_MONTHLY,
    ]);
    $previousInvoice->save();

    // Simulate a payment made 15 days before the new invoice date
    $daysSinceLastPayment = 15;
    $paymentDate = Carbon::now()->subDays($daysSinceLastPayment);
    $payment = new Payment([
        'reference_id' => fake()->uuid(),
        'invoice_id' => $previousInvoice->id,
        'payment_datetime' => $paymentDate,
        'is_cancel' => false,
        'payment_method_id' => $paymentMethod->id,
        'total_amount' => $price + $fee->amount,
        'price' => $price,
        'fee_id' => $fee->id
    ]);
    $payment->save();

    $invoiceService = new InvoiceService(new DiscountService());

    // Act
    // Generate a new invoice for the current month
    $invoice = $invoiceService->generateInvoiceForCustomerPackage(
        $customerPackage,
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth(),
        Carbon::now()
    );

    // Assert
    // The payment was 15 days ago, so there are 15 days of credit in a 30-day cycle.
    $daysInBillingCycle = 30;
    $dailyRate = $price / $daysInBillingCycle;
    $overlappingDays = $daysInBillingCycle - $daysSinceLastPayment;
    $creditAmount = $dailyRate * $overlappingDays;
    $expectedAmount = $price - $creditAmount;
    $expectedAmount = $expectedAmount < 10000 ? 10000 : $expectedAmount;

    expect(round($invoice->amount, 2))->toBe(round($expectedAmount, 2));
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'amount' => $invoice->amount,
    ]);
});

it('charges full amount if last payment was more than a billing cycle ago', function () {
    // Arrange
    $paymentMethod = PaymentMethod::create([
        'name' => 'Test Method Full',
        'code' => 'test-full',
        'category' => PaymentMethod::BANK,
        'is_active' => true,
    ]);
    $fee = $paymentMethod->fee()->create([
        'amount' => 2500,
        'unit' => Fee::NOMINAL,
        'started_at' => now()
    ]);

    $price = 100000;
    $pppProfile = \App\Models\PppProfile::factory()->create();
    $package = Packages::factory()->create(['price' => $price, 'ppp_profile_id' => $pppProfile->id]);
    $customer = Customer::factory()->create();
    $customerPackage = $customer->customerPackages()->first();

    // Create a previous invoice for the customer
    $previousInvoiceDate = Carbon::now()->subMonths(2);
    $previousInvoice = new Invoices([
        'customer_package_id' => $customerPackage->id,
        'customer_id' => $customer->id,
        'invoice_date' => $previousInvoiceDate->toDateString(),
        'due_date' => $previousInvoiceDate->copy()->addDays(30)->toDateString(),
        'period_start' => $previousInvoiceDate->copy()->startOfMonth()->toDateString(),
        'period_end' => $previousInvoiceDate->copy()->endOfMonth()->toDateString(),
        'status' => Invoices::STATUS_PAID,
        'amount' => $price,
        'invoice_number' => 'INV-TEST-FULL',
        'invoice_type' => Invoices::TYPE_MONTHLY,
    ]);
    $previousInvoice->save();

    // Simulate a payment made more than 30 days before the new invoice date
    $paymentDate = Carbon::now()->subDays(31);
    $payment = new Payment([
        'reference_id' => fake()->uuid(),
        'invoice_id' => $previousInvoice->id,
        'payment_datetime' => $paymentDate,
        'is_cancel' => false,
        'payment_method_id' => $paymentMethod->id,
        'total_amount' => $price + $fee->amount,
        'price' => $price,
        'fee_id' => $fee->id
    ]);
    $payment->save();

    $invoiceService = new InvoiceService(new DiscountService());

    // Act
    // Generate a new invoice for the current month
    $invoice = $invoiceService->generateInvoiceForCustomerPackage(
        $customerPackage,
        Carbon::now()->startOfMonth(),
        Carbon::now()->endOfMonth(),
        Carbon::now()
    );

    // Assert
    // The last payment was more than 30 days ago, so the full amount should be charged.
    expect(floatval($invoice->amount))->toBe(floatval($price));
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'amount' => $price,
    ]);
});
