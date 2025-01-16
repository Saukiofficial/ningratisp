<?php

namespace App\Services;

use App\Jobs\GenerateMikrotikVoucherJob;
use App\Models\LogMidtrans;
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

    public function __construct()
    {
        if (app()->environment('production')) {
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

            default:
                $driver = $driver->post($url, $data);
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

        LogMidtrans::create([
            'orderid' => $this->getOrderId(),
            'request' => json_encode($data),
            'response' => json_encode($response),
        ]);

        return $response;
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

        $orderIdArr = explode('-', $data['order_id']);
        $orderId = $orderIdArr[1] ?? null;
        $this->setOrderId($orderId);
        $orderIdType = $orderIdArr[0];
        $voucherService = new VoucherService();
        $voucher = $voucherService->buildData()->where([
            'order_id' => $orderId,
            'status' => '0'
        ])->first();
        if (empty($voucher)) {
            return false;
        }

        $voucher->status = true;
        $success = $voucher->save();

        LogMidtrans::create([
            'orderid' => $this->getOrderId(),
            'request' => json_encode($data),
            'act' => LogMidtrans::CALLBACK
        ]);

        if ($success && strtoupper($orderIdType) == self::VOUCHER) {
            GenerateMikrotikVoucherJob::dispatch($voucher->code, $voucher->duration, $voucher->duration_type);
        }

        // proses payment
        $payService = new PaymentService();
        $payRecord = [
            'total_amount' => $data['gross_amount'],
            'reference_id' => $data['transaction_id'],
            'payment_datetime' => $data['transaction_time'],
            'voucher_id' => $voucher->id,
            'price' => $voucher->price,
            'fee_id' => $voucher->fee_id
        ];
        $payService->save($payRecord);

        return $success;
    }

    public function setOrderId($orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
