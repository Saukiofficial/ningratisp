<?php

namespace App\Http\Controllers;

use App\Helpers\TaxCalculate;
use App\Http\Requests\MidtransCallbackRequest;
use App\Http\Requests\VoucherRequest;
use App\Models\LogIpaymu;
use App\Models\Voucher;
use App\Services\HotspotService;
use App\Services\IPayMuService;
use App\Services\MidtransService;
use App\Services\Model\PaymentMethodService;
use App\Services\Model\VoucherService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HotspotController extends Controller
{
    protected $service;

    public function __construct()
    {
        // $this->service = new HotspotService;
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
        return view('hotspot/landing-payment-link', $data);
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

    public function getVoucherDetails(VoucherRequest $request, VoucherService $service)
    {
        // $challenge = $request->validated('challenge');
        $orderId = $request->validated('orderid');
        // $challengeKey = hash('sha512', env('CHALLENGE_VOUCHER') . $orderId);
        // if ($challenge != $challengeKey) {
        //     abort(403);
        // }

        $response = [
            'error' => true,
            'message' => 'Vocuher tidak ditemukan'
        ];
        $voucher = $service->buildData()->where(['order_id' => $orderId])
            ->first(['order_id', 'code', 'duration', 'description', 'status']);

        if (!empty($voucher)) {
            $response['error'] = empty($voucher->status) ? true : false;
            $response['message'] = empty($voucher->status) ? 'Voucher belum dibayar' : 'Sukses';
            $response['data'] = $voucher;
        }

        return response()->json($response);
    }

    public function confirmPayment(Request $request)
    {
        $uniqNumber = fake()->numerify('###');
        $data['orderid'] = fake()->bothify('#?#????###?#?#?');
        $data['price'] = $request->price . "000";
        $data['title'] = $request->title;

        return view('temp.confirm-payment', $data);
    }

    public function createInvoice(Request $request, IPayMuService $service)
    {
        $expired = now()->addHours(4)->toIso8601String();
        $response = $service->paymentLink($request->orderid, $request->amount, $expired);
        if (!isset($response['Data']['Url'])) {
            abort(400);
        }

        return redirect($response['Data']['Url']);
    }

    public function ipaymuCallback(Request $request, IPayMuService $service)
    {
        $log = LogIpaymu::create([
            'orderid' => $request->reference_id,
            'request' => json_encode($request->all()),
            'act' => LogIpaymu::CALLBACK
        ]);

        return response()->json(['success' => 'ok', 'data' => $log->toArray() ?? []]);
    }

    public function ipaymuInfoLog(Request $request, $orderid)
    {
        $log = LogIpaymu::with([])->where([
            'orderid' => $orderid,
            'act' => LogIpaymu::CALLBACK,
        ])->orderBy('id', 'desc')->first(['request']);

        return response()->json([
            'success' => empty($log) ? false : true,
            'data' => empty($log) ? [] : json_decode($log['request'])
        ]);
    }
}
