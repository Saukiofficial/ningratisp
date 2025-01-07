<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function fee()
    {
        return $this->hasMany(Fee::class)
            ->where('started_at', '<=', now()->format('Y-m-d H:i:s'))
            ->where('is_active', '=', 1)
            ->orderBy('started_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getCategoryName()
    {
        return [
            self::BANK => 'Virtual Akun dan Bank Transfer',
            self::E_WALLET => 'E-Wallet / Dompet Digital',
            self::PAYLATER => 'Paylater',
            self::CREDIT_CARD => 'Kartu Kredit',
            self::RETAIL => 'Toko Retail',
            self::OTHERS => 'Pembayaran Lainnya'
        ];
    }

    public function logoPath(): Attribute
    {
        return new Attribute(get: fn() => asset('assets/img/logo-channels/' . $this->logo));
    }
}
