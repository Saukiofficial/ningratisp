<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPackages extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SUSPENDED = 'suspended';

    public static function getStatusLabel(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_SUSPENDED => 'Ditangguhkan'
        ];
    }
}
