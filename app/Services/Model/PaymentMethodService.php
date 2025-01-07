<?php

namespace App\Services\Model;

use App\Models\PaymentMethod;

class PaymentMethodService extends BaseModelService
{
    public function __construct()
    {
        $this->model = new PaymentMethod();
    }

    public function getCategory(string $name = null)
    {
        $categories = (new PaymentMethod())->getCategoryName();
        if ($name) {
            return $categories[$name] ?? null;
        }

        return $categories;
    }
}
