<?php

namespace App\Services;

use App\Helpers\MikrotikAPI;
use App\Jobs\GenerateMikrotikVoucherJob;
use App\Jobs\SendWhatsappMessageJob;
use App\Models\Fee;
use App\Models\Invoices;
use App\Models\LogMidtrans;
use App\Models\PaymentMethod;
use App\Services\Model\PaymentService;
use App\Services\Model\VoucherService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class MidtransService
{
    const VOUCHER = 'VOC';
    const INVOICE = 'INV';

    protected $baseUrl;
    protected $credentials;
    protected $pathUrl;

    // optional property
    protected $orderId;
    protected bool $shouldLog = true;

    public function __construct()
    {
        if (env('APP_ENV') == 'production') {
            $this->baseUrl = env('MTRANS_URL');
            $credentials = [
                'merchant_id' => env('MTRANS_MERCHANT_ID'),
                'client_key' => env('MTRANS_CLIENT'),
                'server_key' => env('MTRANS_KEY')
            ];
        } else {
            $this->baseUrl = env('MTRANS_URL_STG');
            $credentials = [
                'merchant_id' => env('MTRANS_MERCHANT_ID_STG'),
                'client_key' => env('MTRANS_CLIENT_STG'),
                'server_key' => env('MTRANS_KEY_STG')
            ];
        }

        $this->credentials = $credentials;
    }

    public function request($data, $method = 'post')
    {
        $url = $this->baseUrl . $this->pathUrl;
        $token = base64_encode($this->credentials['server_key'] . ':');
        $driver = Http::withToken($token, 'Basic');

        switch (strtolower($method)) {
            case 'delete':
                $driver = $driver->delete($url, $data);
                break;
            case 'put':
                $driver = $driver->put($url, $data);
                break;
            case 'patch':
                $driver = $driver->patch($url, $data);
                break;
            case 'get':
                $driver = $driver->get($url, $data);
                break;

            default:
                $dataToSend = empty($data) ? new \stdClass() : $data;
                $driver = $driver->post($url, $dataToSend);
                break;
        }

        try {
            $response = $driver->json();
        } catch (Exception $e) {
            $response = [
                'responseCode' => '',
                'responseMessage' => $e->getMessage()
            ];
        }

        if (empty($response)) {
            $response = [
                'responseCode' => '',
                'responseMessage' => ''
            ];
        }

        if ($this->shouldLog) {
            LogMidtrans::create([
                'orderid' => $this->getOrderId(),
                'request' => json_encode($data),
                'response' => json_encode($response),
            ]);
        }

        return $response;
    }

    public function setShouldLog($state = true): void
    {
        $this->shouldLog = $state;
    }

    public function paymentLink($uniqueId, $amount, $expired_at, $desc = null, $channels = ['other_qris'])
    {
        $expired_at = Carbon::parse($expired_at);
        $now = now();
        $duration = $expired_at->diffInHours($now);
        $this->pathUrl = '/v1/payment-links';
        $orderId = "VOC-{$uniqueId}";
        $this->setOrderId($uniqueId);

        $data = [
            "transaction_details" => [
                "order_id" => $orderId,
                "gross_amount" => $amount,
                "payment_link_id" => strtolower($orderId),
            ],
            "customer_required" => false,
            "usage_limit" => 1,
            "expiry" => [
                "start_time" => $expired_at->format('Y-m-d H:i:s P'),
                "duration" => $duration,
                "unit" => "hours",
            ],
            "item_details" => [
                [
                    "name" => $desc,
                    "price" => $amount,
                    "quantity" => 1,
                    "category" => "Voucher",
                ]
            ],
            "enabled_payments" => $channels
        ];

        $response = $this->request($data);

        return $response;
    }

    public function handleNotification($data = null)
    {
        if (!isset($data['transaction_status']) || ($data['transaction_status'] != 'settlement')) {
            return false;
        }

        // validasi data
        $payload = [$data['order_id'], $data['status_code'], $data['gross_amount'], $this->credentials['server_key']];
        $validPayload = hash('SHA512', implode('', $payload));
        if ($data['signature_key'] != $validPayload) {
            return false;
        }

        $orderIdArr = explode('#', $data['order_id']);
        $orderId = $orderIdArr[1] ?? null;
        $this->setOrderId($orderId);
        $orderIdType = $orderIdArr[0];

        LogMidtrans::create([
            'orderid' => $this->getOrderId(),
            'request' => json_encode($data),
            'act' => LogMidtrans::CALLBACK
        ]);

        $handler = null;
        if (strtoupper($orderIdType) == self::VOUCHER) {
            $handler = new \App\Services\Midtrans\VoucherNotificationHandler();
        } elseif (strtoupper($orderIdType) == self::INVOICE) {
            $handler = new \App\Services\Midtrans\InvoiceNotificationHandler();
        }

        if ($handler) {
            return $handler->handle($orderId, $data);
        }

        return false;
    }

    public function setOrderId($orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function generateQRIS($uniqueId, $amount, $preOrderId = 'VOC')
    {
        $this->pathUrl = '/v2/charge';
        $orderId = "$preOrderId#{$uniqueId}";
        $this->setOrderId($uniqueId);

        $data = [
            "transaction_details" => [
                "order_id" => $orderId,
                "gross_amount" => $amount,
            ],
            "payment_type" => 'gopay'
        ];

        $response = $this->request($data);
        return $response;
    }

    public function chargeVirtualAccount($orderId, Invoices $invoice, PaymentMethod $paymentMethod, $feeAmount = 0)
    {
        $this->pathUrl = '/v2/charge';
        $this->setOrderId($orderId);

        $customer = $invoice->customerPackage->customer;

        $data = [
            "transaction_details" => [
                "order_id" => self::INVOICE . "#{$orderId}",
                "gross_amount" => $invoice->balance_due + $feeAmount,
            ],
            "customer_details" => [
                "first_name" => $customer->full_name ?? $customer->user_name,
                "email" => $customer->email,
                "phone" => $customer->phone,
            ],
            "item_details" => $invoice->items->map(function ($item) use ($invoice) {
                return [
                    'id' => $item->id,
                    'price' => $item->unit_price - $invoice->discount_amount,
                    'quantity' => 1,
                    'name' => $item->description . (!empty($invoice->discount_amount) ? ' (Diskon)' : null),
                ];
            })->toArray(),
        ];

        // add fee into item details
        $data['item_details'][] = [
            'price' => $feeAmount,
            'quantity' => 1,
            'name' => 'Fee ' . $paymentMethod->name
        ];

        switch ($paymentMethod->midtrans_code) {
            case 'bca_va':
                $data['payment_type'] = 'bank_transfer';
                $data['bank_transfer'] = ['bank' => 'bca'];
                break;
            case 'bri_va':
                $data['payment_type'] = 'bank_transfer';
                $data['bank_transfer'] = ['bank' => 'bri'];
                break;
            case 'mandiri':
                $data['payment_type'] = 'echannel';
                $data['echannel'] = ['bill_info1' => 'Payment for:', 'bill_info2' => 'Invoice #' . $invoice->invoice_number];
                break;
            default:
                $data['payment_type'] = 'gopay';
                break;
        }

        $response = $this->request($data);
        return $response;
    }

    public function cancelVirtualAccount($orderId)
    {
        $this->pathUrl = "/v2/{$orderId}/cancel";
        $this->setOrderId($orderId);

        $response = $this->request([], 'post');
        return $response;
    }

    public function getStatusVirtualAccount($orderId)
    {
        $this->pathUrl = "/v2/{$orderId}/status";
        $this->setShouldLog(false);

        $response = $this->request([], 'get');
        return $response;
    }
}
