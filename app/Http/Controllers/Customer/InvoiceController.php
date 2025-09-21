<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Customer\Invoices;
use App\Models\PaymentMethod;
use App\Models\VirtualAccount;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $pendingVA = VirtualAccount::whereHas('invoice.customerPackage.customer', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->where('status', 'pending')->where('expired_at', '>', now())->first();

        if ($pendingVA) {
            return to_route('pending-payment.show', $pendingVA);
        }

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

        $invoices = $query->latest('invoice_date')->paginate(10)->withQueryString();

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
            'discount_amount' => $invoice->discount,
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
            return to_route('invoices.show', $invoice)->with('error', 'Invoice already paid.');
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

        return Inertia::render('Customer/Invoices/Checkout', [
            'invoice' => $invoice,
            'paymentMethods' => $paymentMethods,
            'flash' => $this->getFlash()
        ]);
    }

    public function pay(Request $request, Invoices $invoice, MidtransService $midtransService)
    {
        if ($invoice->status === Invoices::STATUS_PAID) {
            return to_route('invoices.show', $invoice)->with('error', 'Invoice already paid.');
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

            return to_route('pending-payment.show', $va)->with('success', 'Virtual Account created successfully.');
        } else {
            return to_route('invoices.checkout', $invoice)->with('error', 'Failed to create Virtual Account (' . $response['status_code'] . ').');
        }
    }
}
