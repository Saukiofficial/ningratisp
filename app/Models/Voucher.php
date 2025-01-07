<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // duration type
    const DAYS = 'd';
    const HOUR = 'h';

    protected $guarded = ['id'];

    /**
     * Harga voucher tersedia (temp)
     * jam - harga
     */
    public static function availPrices(): array
    {
        return [
            3 => 3000,
            5 => 5000,
            10 => 10000
        ];
    }
}
