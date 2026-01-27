<?php

namespace App\Services\Model;

use App\Models\Voucher;

class VoucherService extends BaseModelService
{
    public function __construct()
    {
        $this->model = new Voucher();
    }
};
