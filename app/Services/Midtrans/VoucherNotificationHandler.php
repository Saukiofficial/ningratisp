<?php

namespace App\Services\Midtrans;

use App\Helpers\MikrotikAPI;
use App\Jobs\SendWhatsappMessageJob;
use App\Models\PaymentMethod;
use App\Services\Model\PaymentService;
use App\Services\Model\VoucherService;

class VoucherNotificationHandler implements NotificationHandlerInterface
{
    public function handle(string $orderId, array $data): bool
    {
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

        // NOTE: payment method only qris
        $methodId = PaymentMethod::query()->where('code', 'qris')->first()?->id ?? null;

        if ($success) {
            $service = new MikrotikAPI();
            $createdVoucher = $service->createVoucher($voucher->code, $voucher->duration, $voucher->duration_type);
            if (!empty($createdVoucher['name']) && $createdVoucher['name'] == $voucher->code) {
                $voucher->status = true;
                SendWhatsappMessageJob::dispatch($voucher);
            }
        }

        if ($success) {
            // proses payment
            $payService = new PaymentService();
            $payRecord = [
                'total_amount' => $data['gross_amount'],
                'reference_id' => $data['transaction_id'],
                'payment_datetime' => $data['transaction_time'],
                'voucher_id' => $voucher->id,
                'price' => $voucher->price,
                'fee_id' => $voucher->fee_id,
                'description' => 'Voucher payment',
                'payment_method_id' => $methodId,
                'is_manual' => false
            ];
            $payService->save($payRecord);
        }

        return $success;
    }
}
