<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;

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
