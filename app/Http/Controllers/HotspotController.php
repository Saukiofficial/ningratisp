<?php

namespace App\Http\Controllers;

use App\Helpers\TaxCalculate;
use App\Http\Requests\MidtransCallbackRequest;
use App\Http\Requests\VoucherRequest;
use App\Jobs\DeleteQrisImageJob;
use App\Models\PaymentMethod;
use App\Models\Voucher;
use App\Services\HotspotService;
use App\Services\MidtransService;
use App\Services\Model\PaymentMethodService;
use App\Services\Model\VoucherService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class HotspotController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new HotspotService;
    }

    public function preVoucherRequest(VoucherRequest $request, PaymentMethodService $service)
    {
        $prices = (new Voucher)->pricesDetail();
        if (! isset($prices[$request->validated('pointer')])) {
            return false;
        }
        $price = $prices[$request->validated('pointer')];

        $channels = $service->getAll();
        $data['channels'] = $channels->where('is_active')
            ->where('code', '!=', PaymentMethod::CASH)
            ->groupBy('category');
        $data['categories'] = $service->getCategory();
        $data['price'] = $price['price'];
        $data['pointer'] = $request->validated('pointer');
        $data['sealcode'] = fake()->unique()->bothify('?#?#??##?#?#??##');
        $data['priceDetail'] = $price;

        return view('hotspot/landing-payment-link-static', $data);
    }

    public function voucherRequestQris(VoucherRequest $request, HotspotService $service)
    {
        $pmService = new PaymentMethodService;
        $voucher = $service->generateVoucher($request->validated('pointer'));
        $channelId = 'qris';
        $channel = $pmService->buildData()->where('code', '=', $channelId)->firstOrFail();
        $fee = $channel->fee;
        $total = TaxCalculate::calculate($voucher->price, $fee->amount, $fee->unit);
        if (empty($voucher)) {
            throw new Exception('error');
        }
        $sealcode = $request->seal_code;
        $voucher->fill([
            'seal_code' => $sealcode,
            'fee_id' => $fee->id,
            'whatsapp_number' => $request->whatsapp_number ?? null,
        ]);
        $voucher->save();

        try {
            $pg = new MidtransService;
            $response = $pg->generateQRIS($voucher->order_id, $total);
        } catch (Exception $e) {
            $voucher->delete();

            return abort(500);
        }

        if (! isset($response['status_code']) && $response['status_code'] != '201' && $response['fraud_status'] != 'accept') {
            return abort(403);
        }

        $imgUrl = $statusUrl = '';
        foreach ($response['actions'] as $action) {
            if ($action['name'] == 'generate-qr-code-v2') {
                $imgUrl = $action['url'];
            } elseif ($action['name'] == 'generate-qr-code') {
                $imgUrl = $action['url'];
            }
            if ($action['name'] == 'get-status') {
                $statusUrl = $action['url'];
            }
        }

        if ($imgUrl) {
            try {
                $responseHttp = Http::get($imgUrl);
                if ($responseHttp->successful()) {
                    $imageContent = $responseHttp->body();
                    $fileName = 'qris-'.$voucher->order_id.'.png';
                    $filePath = 'qris/'.$fileName;
                    Storage::disk('public')->put($filePath, $imageContent);

                    $imgUrl = Storage::disk('public')->url($filePath);

                    DeleteQrisImageJob::dispatch($filePath)->delay(now()->addMinutes(15));
                }
            } catch (Exception $e) {
                // Keep original imgUrl if download fails
            }
        }

        $voucher->fill(['external_link' => $imgUrl]);
        $voucher->save();
        $data['url'] = $imgUrl;
        $data['urlStatus'] = $statusUrl;

        return response()->json($data);
    }

    public function voucherRequest(VoucherRequest $request, HotspotService $service)
    {
        $pmService = new PaymentMethodService;
        $voucher = $service->generateVoucher($request->validated('pointer'));
        $channelId = $request->validated('channel_id');
        $channel = $pmService->get($channelId);
        $fee = $channel->fee;
        $total = TaxCalculate::calculate($voucher->price, $fee->amount, $fee->unit);
        if (empty($voucher)) {
            throw new Exception('error');
        }
        $sealcode = $request->seal_code;
        $voucher->fill(['seal_code' => $sealcode, 'fee_id' => $fee->id]);
        $voucher->save();

        $data['others'] = "<script>localStorage.setItem('orderid', '{$voucher->order_id}')</script>";

        try {
            $pg = new MidtransService;
            $response = $pg->paymentLink($voucher->order_id, $total, $voucher->expired_at, $voucher->description, [$channel->midtrans_code]);
        } catch (Exception $e) {
            return abort(500);
        }

        if (! isset($response['payment_url'])) {
            return abort(403);
        }

        $url = $response['payment_url'];
        $voucher->fill(['external_link' => $url]);
        $voucher->save();
        $data['url'] = $url;

        return view('redirectorjs', $data);
    }

    public function midtransCallback(MidtransCallbackRequest $request, MidtransService $service)
    {
        $service->handleNotification($request->all());

        return response()->json();
    }

    public function getVoucherDetails($sealcode, VoucherService $service)
    {
        $response = [
            'error' => true,
            'message' => 'Voucher tidak ditemukan',
        ];
        $voucherModel = $service->buildData()->where(['seal_code' => $sealcode])->first();

        if ($voucherModel) {
            // Direct check to Midtrans if unpaid
            if (empty($voucherModel->status)) {
                try {
                    $pg = new MidtransService;
                    $midtransStatus = $pg->getStatusVirtualAccount("VOC_{$voucherModel->order_id}");
                    if (isset($midtransStatus['transaction_status']) && in_array($midtransStatus['transaction_status'], ['settlement', 'capture'])) {
                        $pg->handleNotification($midtransStatus);
                        $voucherModel = $service->buildData()->where(['seal_code' => $sealcode])->first();
                    }
                } catch (Exception $e) {
                    // Ignore Midtrans network errors gracefully
                }
            }

            $fee = $voucherModel->fee;
            $voucher = [
                'order_id' => $voucherModel->order_id,
                'code' => empty($voucherModel->status) ? null : $voucherModel->code,
                'duration' => $voucherModel->duration,
                'description' => empty($voucherModel->status) ? explode('|', $voucherModel->description)[0] : $voucherModel->description,
                'status' => $voucherModel->status,
                'price' => $voucherModel->price,
            ];

            $total_amount = $fee ? TaxCalculate::calculate($voucherModel->price, $fee->amount, $fee->unit) : $voucherModel->price;
            $voucher['total_amount'] = number_format($total_amount, 0, ',', '.');

            $response['error'] = empty($voucherModel->status) ? true : false;
            $response['message'] = empty($voucherModel->status) ? 'Voucher belum dibayar' : 'Voucher lunas';
            $response['data'] = $voucher;
        }

        return response()->json($response);
    }

    public function cancelVoucher($sealcode, VoucherService $service)
    {
        $voucher = $service->buildData()->where(['seal_code' => $sealcode])->first();

        if ($voucher && empty($voucher->status)) {
            try {
                $pg = new MidtransService;
                $pg->cancelVirtualAccount("VOC_{$voucher->order_id}");
            } catch (Exception $e) {
                // Ignore if already cancelled or expired on Midtrans
            }

            $voucher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi pembayaran berhasil dibatalkan.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Voucher tidak dapat dibatalkan atau sudah dilunasi.',
        ], 400);
    }

    public function checkInvoice(Request $request, VoucherService $service)
    {
        $invoiceRequest = str_replace(
            'inv-',
            '',
            strtolower($request->invoice_number)
        );

        $voucher = $service->buildData('payment')
            ->where('order_id', $invoiceRequest)
            ->first();
        if (empty($voucher) || empty($voucher->payment()->exists())) {
            return [
                'status' => 'fail',
            ];
        }

        return ['status' => 'success', 'voucher_code' => $voucher->code];
    }
}
