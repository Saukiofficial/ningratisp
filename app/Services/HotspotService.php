<?php

namespace App\Services;

use App\Models\Voucher;
use Illuminate\Support\Facades\Http;

class HotspotService
{
    /**
     * Generate voucher berdasarkan jam
     * 
     * @param int $hour masa berlaku voucher dalam Jam
     * @param int $expiredPayment masa batas pembayaran dalam jam
     * @return false|App\Models\Voucher
     */
    public function generateVoucher($hour, $expiredPayment = 3)
    {
        $prices = Voucher::availPrices();
        $model = new Voucher();
        if (!isset($prices[$hour])) {
            return false;
        }

        $orderId = fake()->unique()->bothify(fake()->randomElement(['?##???#?#??#??##?', '###?#?#????###?##', '#???#??##???#?##?']));
        $code = $this->generateCode(false);
        $created_at = now();
        $desc = "Voucher {$hour} Jam | Kupon : " . $code;

        $record = [
            'order_id' => $orderId,
            'code' => $code,
            'expired_at' => $created_at->addHours($hour),
            'description' => $desc,
            'duration' => $hour,
            'price' => $prices[$hour],
            'status' => false
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
