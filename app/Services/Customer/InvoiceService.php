<?php

namespace App\Services\Customer;

use App\Models\Invoices;

class InvoiceService
{

    protected Invoices $model;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->model = new Invoices();
    }

    // public function 
}
