<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_SETTLEMENT = 'settlement';
    const STATUS_EXPIRE = 'expire';
    const STATUS_CANCEL = 'cancel';
    const STATUS_DENY = 'deny';

    protected $guarded = ['id'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
