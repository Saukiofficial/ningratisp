<?php

namespace App\Http\Controllers;

use App\Helpers\TaxCalculate;
use App\Http\Requests\MidtransCallbackRequest;
use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;
use App\Services\HotspotService;
use App\Services\MidtransService;
use App\Services\Model\PaymentMethodService;
use App\Services\Model\VoucherService;
use Exception;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new HotspotService;
    }

    public function preVoucherRequest(VoucherRequest $request, PaymentMethodService $service)
    {
        $prices = (new Voucher())->pricesDetail();
        if (!isset($prices[$request->validated('pointer')])) {
            return false;
        }
        $price = $prices[$request->validated('pointer')];

        $channels = $service->getAll();
        $data['channels'] = $channels->where('is_active')->groupBy('category');
        $data['categories'] = $service->getCategory();
        $data['price'] = $price['price'];
        $data['pointer'] = $request->validated('pointer');
        $data['sealcode'] = fake()->unique()->bothify('?#?#??##?#?#??##');
        $data['priceDetail'] = $price;
        return view('hotspot/landing-payment-link', $data);
    }

    public function voucherRequest(VoucherRequest $request, HotspotService $service)
    {
        $pmService = new PaymentMethodService();
        $voucher = $service->generateVoucher($request->validated('pointer'));
        $channelId = $request->validated('channel_id');
        $channel = $pmService->get($channelId);
        $fee = $channel->fee();
        $total = TaxCalculate::calculate($voucher->price, $fee->amount, $fee->unit);
        if (empty($voucher)) {
            throw new Exception('error');
        }
        $sealcode = $request->seal_code;
        $voucher->fill(['seal_code' => $sealcode, 'fee_id' => $fee->id]);
        $voucher->save();

        $data['others'] = "<script>localStorage.setItem('orderid', '{$voucher->order_id}')</script>";

        try {
            $pg = new MidtransService();
            $response = $pg->paymentLink($voucher->order_id, $total, $voucher->expired_at, $voucher->description, [$channel->midtrans_code]);
        } catch (Exception $e) {
            return abort(500);
        }

        if (!isset($response['payment_url'])) {
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
    }

    public function getVoucherDetails($sealcode, VoucherService $service)
    {
        // $challenge = $request->validated('challenge');
        // $challengeKey = hash('sha512', env('CHALLENGE_VOUCHER') . $orderId);
        // if ($challenge != $challengeKey) {
        //     abort(403);
        // }

        $response = [
            'error' => true,
            'message' => 'Vocuher tidak ditemukan'
        ];
        $voucher = $service->buildData()->where(['seal_code' => $sealcode])
            ->first(['order_id', 'code', 'duration', 'description', 'status']);

        if (!empty($voucher)) {
            $response['error'] = empty($voucher->status) ? true : false;
            $response['message'] = empty($voucher->status) ? 'Voucher belum dibayar' : 'Voucher lunas';
            $voucher->code = empty($voucher->status) ? null : $voucher->code;
            $voucher->description = empty($voucher->status) ? explode('|', $voucher->description)[0] : $voucher->description;
            $response['data'] = $voucher;
        }

        return response()->json($response);
    }
}
