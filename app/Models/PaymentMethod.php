<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    // category
    const BANK = 'b';
    const E_WALLET = 'e';
    const PAYLATER = 'l';
    const CREDIT_CARD = 'c';
    const RETAIL = 'r';
    const OTHERS = 'o';

    protected $guarded = ['id'];
}
