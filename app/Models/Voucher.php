<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Voucher extends Model
{
    use HasFactory;

    // duration type
    const DAYS = 'd';
    const HOUR = 'h';

    protected $guarded = ['id'];

    // voucher uptime
    const H_3 = 'h3';
    const H_6 = 'h6';
    const D_1 = 'd1';
    const D_7 = 'd7';
    const D_30 = 'd30';

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'voucher_id');
    }

    public static function pricesDetail(): array
    {
        return [
            self::H_3 => ['price' => 2000, 'type' => self::HOUR, 'active' => 3, 'label' => '3 Jam'],
            self::H_6 => ['price' => 3000, 'type' => self::HOUR, 'active' => 6, 'label' => '6 Jam'],
            self::D_1 => ['price' => 5000, 'type' => self::DAYS, 'active' => 1, 'label' => '1 Hari (24 Jam)'],
            self::D_7 => ['price' => 15000, 'type' => self::DAYS, 'active' => 7, 'label' => '1 Minggu'],
            self::D_30 => ['price' => 50000, 'type' => self::DAYS, 'active' => 30, 'label' => '30 Hari']
        ];
    }
}
