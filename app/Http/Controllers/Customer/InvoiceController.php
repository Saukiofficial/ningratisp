<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Customer\Invoices;
use App\Models\Discount;
use App\Models\Fee;
use App\Models\PaymentMethod;
use App\Models\VirtualAccount;
use App\Services\DiscountService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function __construct(
        protected DiscountService $discountService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var Customer */
        $user = auth('customers')->user();
        $query = $user->invoices();
        $unpaidQuery = (clone $query)->where('invoices.status', Invoices::STATUS_UNPAID);

        if ($request->filled('status')) {
            $query->where('invoices.status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->where('invoice_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('invoice_date', '<=', $request->end_date);
        }

        $paginationLength = 10;
        $invoicesQuery = $query->with('discount')->latest('invoice_date');
        $hasUnpaidInvoices = $unpaidQuery->exists();
        $totalUnpaidInvoices = $unpaidQuery->count();

        return Inertia::render('Customer/Invoices/Index', [
            'tagihans' => Inertia::scroll(fn () => $invoicesQuery->paginate($paginationLength)->withQueryString()),
            'has_active_invoices' => $hasUnpaidInvoices,
            'total_unpaid_invoices' => $totalUnpaidInvoices,
            'filters' => $request->only(['status', 'start_date', 'end_date']),
            'invoice_statuses' => [
                'paid' => Invoices::STATUS_PAID,
                'unpaid' => Invoices::STATUS_UNPAID,
            ],
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoices $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['items', 'allocations.payment.paymentMethod']);

        $invoiceData = [
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => $invoice->invoice_date,
            'due_date' => $invoice->due_date,
            'status' => $invoice->status,
            'paid_date' => $invoice->status === Invoices::STATUS_PAID ? $invoice->updated_at : null,
            // 'customer' => [
            //     'name' => $invoice->customer->name,
            //     'address' => $invoice->customer->address,
            //     'phone' => $invoice->customer->phone,
            //     'email' => $invoice->customer->email,
            // ],
            'items' => $invoice->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'amount' => $item->unit_price,
                ];
            }),
            'subtotal' => $invoice->subtotal,
            'discount_amount' => $invoice->discount_amount,
            'total' => $invoice->total,
            'paid_amount' => $invoice->paid_amount,
            'balance_due' => $invoice->balance_due,
            'payment_allocations' => $invoice->allocations->map(function ($allocation) {
                return [
                    'id' => $allocation->id,
                    'payment_date' => $allocation->payment->payment_datetime,
                    'payment_method' => $allocation->payment->paymentMethod->name,
                    'amount' => $allocation->amount,
                ];
            }),
        ];

        return Inertia::render('Customer/Invoices/Show', [
            'invoice' => $invoiceData,
            'flash' => $this->getFlash(),

        ]);
    }

    public function checkout(Invoices $invoice)
    {
        if ($invoice->status === Invoices::STATUS_PAID) {
            return to_route('customer.invoices.show', $invoice)->with('error', 'Invoice already paid.');
        }

        $paymentMethods = PaymentMethod::with('fee')
            ->where('is_active', true)
            ->whereNotNull('midtrans_code')
            ->get()->map(function ($pm) {
                return [
                    'id' => $pm->id,
                    'name' => $pm->name,
                    'code' => $pm->midtrans_code,
                    'fee' => $pm->fee,
                ];
            });

        // 1. Count how many times each discount_id is used on ALL other invoices
        $usedCounts = auth()->user()->invoices()
            ->where('invoices.id', '!=', $invoice->id)
            ->whereNotNull('discount_id')
            ->get()
            ->groupBy('discount_id')
            ->map->count();

        // 2. Get all active claims and group them by discount_id
        $groupedClaims = auth()->user()->customerDiscounts()
            ->with('discount')
            ->where('is_active', true)
            ->get()
            ->groupBy('discount_id');

        $availableDiscounts = collect();

        // 3. Determine which claims are actually available
        foreach ($groupedClaims as $discountId => $claims) {
            $discount = $claims->first()?->discount;
            if (! $discount || ! $discount->is_active || ! $discount->isWithinDateRange()) {
                continue;
            }
            if ($discount->usage_limit !== null && $discount->used_count >= $discount->usage_limit) {
                continue;
            }

            $used = $usedCounts->get($discountId, 0);
            $availableCount = $claims->count() - $used;

            if ($availableCount > 0) {
                // Take the number of available claims from the list and add them to our final collection
                $availableClaims = $claims->take($availableCount);
                $availableDiscounts = $availableDiscounts->merge($availableClaims);
            }
        }

        return Inertia::render('Customer/Invoices/Checkout', [
            'invoice' => $invoice->load('discount'),
            'paymentMethods' => $paymentMethods,
            'claimedDiscounts' => $availableDiscounts->values(), // Pass the correctly filtered collection
            'flash' => $this->getFlash(),
        ]);
    }

    public function pay(Request $request, Invoices $invoice, MidtransService $midtransService)
    {
        if ($invoice->status === Invoices::STATUS_PAID) {
            return to_route('customer.invoices.show', $invoice)->with('error', 'Invoice already paid.');
        }

        $paymentMethodId = $request->input('payment_method');
        $discountId = $request->input('discount_id');

        // Handle Discount
        if ($discountId) {
            $customer = auth('customers')->user();
            $discount = Discount::query()->active()->find($discountId);

            if (! $discount) {
                return to_route('customer.invoices.checkout', $invoice)->with('error', 'Invalid or expired discount.');
            }

            // Check if user has this discount claimed (if it's not auto-applied)
            $hasClaim = $customer->discounts()
                ->where('discounts.id', $discountId)
                ->wherePivot('is_active', true)
                ->exists();

            if (! $hasClaim) {
                return to_route('customer.invoices.checkout', $invoice)->with('error', 'You have not claimed this discount.');
            }

            $validation = $this->discountService->validateDiscountForCustomer($discount, $customer, $invoice, true);
            if (! $validation['valid']) {
                return to_route('customer.invoices.checkout', $invoice)->with('error', $validation['message']);
            }

            // Apply but do NOT record usage yet (it will be done when paid)
            $this->discountService->applyDiscountToInvoice($invoice, $discount, false);
            $invoice->refresh();
        } else {
            // Ensure no discount is applied if not requested
            if ($invoice->discount_id) {
                $invoice->discount_id = null;
                $invoice->discount_amount = 0;
                $invoice->recalculateTotals();
                $invoice->save();
            }
        }

        // get payment method and fee
        $paymentMethod = PaymentMethod::query()->findOrFail($paymentMethodId);
        $fee = 0;
        if ($paymentMethod->fee) {
            if ($paymentMethod->fee->unit == Fee::PERCENTAGE) {
                $fee = (floatval($invoice->balance_due) * floatval($paymentMethod->fee->amount)) / 100;
            } else {
                $fee = floatval($paymentMethod->fee->amount);
            }
        }

        $orderId = uuid_create();
        $response = $midtransService->chargeVirtualAccount(
            $orderId,
            $invoice,
            $paymentMethod,
            $fee
        );

        if (isset($response['transaction_id'])) {
            $va = VirtualAccount::create([
                'order_id' => $orderId,
                'payment_method_id' => $paymentMethod->id,
                'invoice_id' => $invoice->id,
                'transaction_id' => $response['transaction_id'],
                'payment_type' => $response['payment_type'],
                'status' => $response['transaction_status'],
                'expired_at' => $response['expiry_time'],
                'total_amount' => $invoice->balance_due + $fee,
                'fee_amount' => $fee,
            ]);

            if (isset($response['va_numbers'])) {
                $va->va_number = $response['va_numbers'][0]['va_number'];
            } elseif (isset($response['permata_va_number'])) {
                $va->va_number = $response['permata_va_number'];
            } elseif ($response['payment_type'] == 'echannel') {
                $va->va_number = $response['biller_code'].$response['bill_key'];
            } elseif (isset($response['actions'])) {
                $urls = [];
                foreach ($response['actions'] as $action) {
                    $urls[$action['name']] = $action['url'];
                }

                if (isset($urls['generate-qr-code-v2'])) {
                    $va->qris_url = $urls['generate-qr-code-v2'];
                } elseif (isset($urls['generate-qr-code'])) {
                    $va->qris_url = $urls['generate-qr-code'];
                }
            }

            $va->save();

            return to_route('customer.pending-payment.show', $va)->with('success', 'Virtual Account created successfully.');
        } else {
            return to_route('customer.invoices.checkout', $invoice)->with('error', 'Failed to create Virtual Account ('.($response['status_code'] ?? json_encode($response)).').');
        }
    }

    public function applyDiscount(Request $request, Invoices $invoice)
    {

        $request->validate([
            'discount_id' => 'required|exists:discounts,id',
        ]);

        $customer = auth()->user();
        $discount = $customer->discounts()->where('discounts.id', $request->discount_id)->wherePivot('is_active', true)->first();

        if (! $discount) {
            return back()->with('error', 'Invalid or expired discount.');
        }

        // Prevent discount from being greater than or equal to the invoice amount
        $subtotal = $invoice->subtotal;
        $discountAmount = 0;

        if ($discount->type === Discount::PERCENTAGE) {
            $discountAmount = ($subtotal * $discount->value) / 100;
            $maxCap = floatval($discount->max_discount_amount);
            if ($maxCap > 0 && $discountAmount > $maxCap) {
                $discountAmount = $maxCap;
            }
        } elseif ($discount->type === Discount::FIXED_AMOUNT) {
            $discountAmount = $discount->value;
        }

        if ($discountAmount >= $subtotal) {
            return back()->with('error', 'The discount not allowed for this invoice');
            // return back()->with('error', 'The discount value cannot be greater than or equal to the invoice amount.');
        }

        $invoice = $this->discountService->customerApplyDiscountToInvoice($invoice, $discount);

        return back()->with('success', 'Discount applied successfully.');
    }

    public function removeDiscount(Request $request, Invoices $invoice)
    {
        $invoice->discount_id = null;
        $invoice->discount_amount = 0;
        $invoice->recalculateTotals();
        $invoice->save();

        return back()->with('success', 'Discount removed.');
    }
}
