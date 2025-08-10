<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatusLabel(): array
    {
        return [
            self::STATUS_UNPAID => 'Belum bayar',
            self::STATUS_PAID => 'Sudah bayar',
            self::STATUS_OVERDUE => 'Mencapai batas bayar',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }
}
