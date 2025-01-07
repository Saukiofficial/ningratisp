<?php

namespace App\Http\Controllers;

use App\Helpers\TaxCalculate;
use App\Http\Requests\MidtransCallbackRequest;
use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;
use App\Services\HotspotService;
use App\Services\MidtransService;
use App\Services\Model\PaymentMethodService;
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
        $prices = (new Voucher())->availPrices();
        if (!isset($prices[$request->validated('hour')])) {
            return false;
        }
        $price = $prices[$request->validated('hour')];

        $channels = $service->getAll();
        $data['channels'] = $channels->where('is_active')->groupBy('category');
        $data['categories'] = $service->getCategory();
        $data['price'] = $price;
        $data['hour'] = $request->validated('hour');
        return view('landing-payment-link', $data);
    }

    public function voucherRequest(VoucherRequest $request, HotspotService $service)
    {
        $pmService = new PaymentMethodService();
        $voucher = $service->generateVoucher($request->validated('hour'));
        $channelId = $request->validated('channel_id');
        $fee = $pmService->get($channelId)->fee();
        $total = TaxCalculate::calculate($voucher->price, $fee->amount, $fee->unit);
        if (empty($voucher)) {
            throw new Exception('error');
        }

        $data['others'] = "<script>localStorage.setItem('orderid', '{$voucher->order_id}')</script>";

        try {
            $pg = new MidtransService();
            $response = $pg->paymentLink($voucher->order_id, $total, $voucher->expired_at, $voucher->description);
        } catch (Exception $e) {
            return abort(500);
        }

        if (!isset($response['payment_url'])) {
            return abort(403);
        }

        $data['url'] = $response['payment_url'];
        return view('redirectorjs', $data);
    }

    public function midtransCallback(MidtransCallbackRequest $request, MidtransService $service)
    {
        $service->handleNotification($request->all());
    }
}
