<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    const CASH = 'cash';

    protected $guarded = ['id'];

    public function fee(): HasOne
    {
        return $this->hasOne(Fee::class)->latestOfMany();
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
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
