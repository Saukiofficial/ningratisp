<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Customer\Invoices;
use App\Models\Discount;
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
        /** @var \App\Models\Customer */
        $user = auth('customers')->user();
        $query = $user->invoices();

        if ($request->filled('status')) {
            $query->where('invoices.status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->where('invoice_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('invoice_date', '<=', $request->end_date);
        }

        $invoices = $query->with('discount')->latest('invoice_date')->paginate(10)->withQueryString();

        return Inertia::render('Customer/Invoices/Index', [
            'tagihans' => $invoices,
            'filters' => $request->only(['status', 'start_date', 'end_date']),
            'invoice_statuses' => [
                'paid' => Invoices::STATUS_PAID,
                'unpaid' => Invoices::STATUS_UNPAID,
            ],
            'flash' => $this->getFlash()
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
            'flash' => $this->getFlash()

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

        // 1. Count how many times each discount_id is used on OTHER unpaid invoices
        $usedCounts = auth()->user()->invoices()
            ->where('invoices.status', Invoices::STATUS_UNPAID)
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
            'flash' => $this->getFlash()
        ]);
    }

    public function pay(Request $request, Invoices $invoice, MidtransService $midtransService)
    {
        if ($invoice->status === Invoices::STATUS_PAID) {
            return to_route('customer.invoices.show', $invoice)->with('error', 'Invoice already paid.');
        }

        $paymentMethod = $request->input('payment_method');

        // get payment method and fee
        $paymentMethod = PaymentMethod::query()->findOrFail($paymentMethod);
        $fee = $paymentMethod->fee ?
            $paymentMethod->fee->unit == Fee::PERCENTAGE :
            0;
        $fee = $paymentMethod->fee?->amount ?? 0;
        if ($fee != 0 && $paymentMethod->fee->unit == Fee::PERCENTAGE) {
            $fee = (floatval($invoice->balance_due) * floatval($paymentMethod->fee->amount)) / 100;
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
                'fee_amount' => $fee
            ]);

            if (isset($response['va_numbers'])) {
                $va->va_number = $response['va_numbers'][0]['va_number'];
            } elseif (isset($response['permata_va_number'])) {
                $va->va_number = $response['permata_va_number'];
            } elseif ($response['payment_type'] == 'echannel') {
                $va->va_number = $response['biller_code'] . $response['bill_key'];
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
            return to_route('invoices.checkout', $invoice)->with('error', 'Failed to create Virtual Account (' . $response['status_code'] . ').');
        }
    }

    public function applyDiscount(Request $request, Invoices $invoice)
    {

        $request->validate([
            'discount_id' => 'required|exists:discounts,id',
        ]);

        $customer = auth()->user();
        $discount = $customer->discounts()->where('discounts.id', $request->discount_id)->wherePivot('is_active', true)->first();

        if (!$discount) {
            return back()->with('error', 'Invalid or expired discount.');
        }

        // Prevent discount from being greater than or equal to the invoice amount
        $subtotal = $invoice->subtotal;
        $discountAmount = 0;

        if ($discount->type === Discount::PERCENTAGE) {
            $discountAmount = ($subtotal * $discount->value) / 100;
            if ($discount->max_discount_amount && $discountAmount > $discount->max_discount_amount) {
                $discountAmount = $discount->max_discount_amount;
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
