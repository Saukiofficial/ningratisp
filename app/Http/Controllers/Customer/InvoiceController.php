<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use App\Models\PaymentMethod;
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
        $pendingInvoice = auth()->user()->invoices()->pending()->first();
        if ($pendingInvoice) {
            return to_route('pending-payment.show', $pendingInvoice);
        }

        $query = auth()->user()->invoices();

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
            'flash' => [
                'success' => session('success'),
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoices $invoice)
    {
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
            'midtrans_transaction_id' => $invoice->midtrans_transaction_id,
            'midtrans_payment_type' => $invoice->midtrans_payment_type,
            'midtrans_va_number' => $invoice->midtrans_va_number,
            'midtrans_expiry_time' => $invoice->midtrans_expiry_time,
            'midtrans_status' => $invoice->midtrans_status,
        ];

        return Inertia::render('Customer/Invoices/Show', [
            'invoice' => $invoiceData,
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
        ]);
    }

    public function pay(Request $request, Invoices $invoice, MidtransService $midtransService)
    {
        if ($invoice->status === Invoices::STATUS_PAID) {
            return to_route('invoices.show', $invoice)->with('error', 'Invoice already paid.');
        }

        $paymentMethod = $request->input('payment_method');

        $response = $midtransService->chargeVirtualAccount(
            $invoice,
            $paymentMethod
        );

        if (isset($response['transaction_id'])) {
            $invoice->midtrans_transaction_id = $response['transaction_id'];
            $invoice->midtrans_payment_type = $response['payment_type'];
            $invoice->midtrans_status = $response['transaction_status'];
            $invoice->midtrans_expiry_time = $response['expiry_time'];

            if (isset($response['va_numbers'])) {
                $invoice->midtrans_va_number = $response['va_numbers'][0]['va_number'];
            } elseif (isset($response['permata_va_number'])) {
                $invoice->midtrans_va_number = $response['permata_va_number'];
            } elseif ($response['payment_type'] == 'echannel') {
                $invoice->midtrans_va_number = $response['biller_code'] . $response['biller_key'];
            } elseif (isset($response['actions'])) {
                foreach ($response['actions'] as $action) {
                    if ($action['name'] === 'generate-qr-code') {
                        $invoice->midtrans_qris_url = $action['url'];
                        break;
                    }
                }
            }

            $invoice->save();

            return to_route('pending-payment.show', $invoice)->with('success', 'Virtual Account created successfully.');
        } else {
            return to_route('invoices.checkout', $invoice)->with('error', 'Failed to create Virtual Account.');
        }
    }
}
