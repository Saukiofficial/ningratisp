<?php

namespace App\Services;

use App\Models\LogIpaymu;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class IPayMuService
{
    const VOUCHER = 'VOC';
    const INVOICE = 'INV';

    protected $baseUrl;
    protected $credentials;
    protected $pathUrl;

    public function __construct()
    {
        if (app()->environment('production')) {
            $this->baseUrl = env('IPAYMU_URL');
            $credentials = [
                'client_key' => env('IPAYMU_CLIENT'),
                'server_key' => env('IPAYMU_KEY')
            ];
        } else {
            $this->baseUrl = env('IPAYMU_URL_STG');
            $credentials = [
                'client_key' => env('IPAYMU_CLIENT_STG'),
                'server_key' => env('IPAYMU_KEY_STG')
            ];
        }

        $this->credentials = $credentials;
    }

    public function request($data, $method = 'post')
    {
        $url = $this->baseUrl . $this->pathUrl;
        $signature = $this->generateSignature($data, $method);
        $ts = now()->format('YmdHis');
        $headers = [
            'va' => $this->credentials['client_key'],
            'signature' => $signature,
            'timestamp' => $ts
        ];
        $driver = Http::withHeaders($headers);

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

        LogIpaymu::create([
            'orderid' => $data['referenceId'],
            'request' => json_encode($data),
            'response' => json_encode($response),
            'act' => LogIpaymu::REQUEST
        ]);

        return $response;
    }

    public function paymentLink($uniqueId, $amount, $expired_at, $product = 'Tagihan', $type = self::VOUCHER, $desc = null)
    {
        $this->pathUrl = '/payment';
        $expired_at = Carbon::parse($expired_at);
        $now = now();
        $duration = $expired_at->diffInHours($now);

        $data = [
            'product' => [$product],
            'qty' => ["1"],
            'price' => [$amount],
            'description' => [$desc],
            'returnUrl' => route('thanks-page'),
            'notifyUrl' => route('ipaymu-callback'),
            'cancelUrl' => route('failed-page'),
            'referenceId' => $uniqueId,
            'buyerName' => "{$type}-{$uniqueId}",
            'expired' => $duration,
            'feeDirection' => 'BUYER'
        ];

        $response = $this->request($data);
        return $response;
    }

    private function generateSignature($data = [], $method = 'POST'): string
    {
        $method = strtoupper($method);
        $vaNumber = $this->credentials['client_key'];
        $apiKey = $this->credentials['server_key'];
        $body = strtolower(hash('sha256', json_encode($data, JSON_UNESCAPED_SLASHES)));
        $stringToSign = [$method, $vaNumber, $body, $apiKey];
        $stringToSign = implode(':', $stringToSign);
        $signature = hash_hmac('sha256', $stringToSign, $apiKey);

        return $signature;
    }
}
