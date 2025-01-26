<?php

namespace App\Services;

use App\Models\Voucher;
use Illuminate\Support\Facades\Http;

class HotspotService
{
    /**
     * Generate voucher berdasarkan jam
     * 
     * @param int $pointer pointer masa berlaku voucher (Hour and Day)
     * @param int $expiredPayment masa batas pembayaran dalam jam
     * @return \App\Models\Voucher|false
     */
    public function generateVoucher($pointer, $expiredPayment = 3)
    {
        $pricesDetail = Voucher::pricesDetail();
        $model = new Voucher();
        if (!isset($pricesDetail[$pointer])) {
            return false;
        }

        $pDetail = $pricesDetail[$pointer];
        $orderId = fake()->unique()->bothify(fake()->randomElement(['?##???#?#??#??##?', '###?#?#????###?##', '#???#??##???#?##?']));
        $code = $this->generateCode(false);
        $created_at = now();
        $expired_at = $pDetail['type'] == Voucher::DAYS ? $created_at->addDays($pDetail['active']) : $created_at->addHours($pDetail['active']);
        $desc = "Voucher {$pointer} " . ($pDetail['type'] == Voucher::DAYS ? 'Hari' : 'Jam') . " | Kupon : " . $code;

        $record = [
            'order_id' => $orderId,
            'code' => $code,
            'expired_at' => $expired_at,
            'description' => $desc,
            'duration' => $pDetail['active'],
            'duration_type' => ($pDetail['type'] == Voucher::DAYS ? Voucher::DAYS : Voucher::HOUR),
            'price' => $pDetail['price'],
            'status' => false,
        ];

        $model->fill($record);
        $model->save();
        return $model;
    }

    /**
     * Generate kode voucher dengan panjang 6 digit
     * 
     */
    public function generateCode($checkDb = true, $maxRetries = 10)
    {
        $patterns = [
            "?##?#?",
            "##????",
            "#?#?##",
            "#??#?#",
            "####?#",
            "##?###",
            "?#????"
        ];

        $attempt = 0;

        do {
            $code = fake()->unique()->bothify(fake()->randomElement($patterns));

            if ($checkDb) {
                $exist = Voucher::where(['code' => $code])->first();
                if (!$exist) {
                    return $code;
                }
            } else {
                return $code;
            }

            $attempt++;
        } while ($attempt < $maxRetries);

        return false;
    }
}
