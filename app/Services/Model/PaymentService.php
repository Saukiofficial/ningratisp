<?php

namespace App\Services\Model;

use App\Models\Payment;

class PaymentService extends BaseModelService
{
    public function __construct()
    {
        $this->model = new Payment();
    }

    public function savePayment($data) {}

    public function typeResources(): array
    {
        return [
            'credit_card' => [
                'code' => '',
                'source_string' => ''
            ],
            'gopay' => [
                'code' => 'gopay',
                'source_string' => ''
            ],
            'qris' => [
                'code' => 'qris',
                'source_string' => ''
            ],
            'shopeepay' => [
                'code' => 'shopeepay',
                'source_string' => ''
            ],
            'bank_transfer' => [
                'code' => '',
                'source_string' => ''
            ],
            'credit_card' => [
                'code' => '',
                'source_string' => ''
            ],
            'credit_card' => [
                'code' => '',
                'source_string' => ''
            ],
            'credit_card' => [
                'code' => '',
                'source_string' => ''
            ],
        ];
    }
};
