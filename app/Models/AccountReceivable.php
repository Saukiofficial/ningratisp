<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountReceivable extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $ar) {
            $ar->recalculate();
        });
    }

    public function recalculate(): void
    {
        $amount = (float) ($this->amount ?? 0);
        $paid = (float) ($this->amount_paid ?? 0);
        $this->balance = round($amount - $paid, 2);

        if ($this->status !== 'written_off') {
            if ($this->balance <= 0) {
                $this->status = 'paid';
            } elseif ($paid > 0) {
                $this->status = 'partial';
            } else {
                $this->status = 'open';
            }

            $today = now()->toDateString();
            if ($this->due_date && $this->due_date->toDateString() < $today && $this->balance > 0) {
                $this->status = 'overdue';
            }
        }
    }
}
