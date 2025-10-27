<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    const ITEM_CHARGE = 'charge';
    const ITEM_DISCOUNT = 'discount';
    const ITEM_TAX = 'tax';
    const ITEM_ADJUSTMENT = 'adjustment';

    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quantity'   => 'decimal:2',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }

    public static function getItemLabel(): array
    {
        return [
            self::ITEM_ADJUSTMENT => 'Adjustment (Penyesuaian)',
            self::ITEM_CHARGE => 'Charge (Biaya tagihan)',
            self::ITEM_DISCOUNT => 'Discount (Potongan tagihan)',
            self::ITEM_TAX => 'Tax (Pajak tagihan)'
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            // Default line_total to quantity * unit_price for charge/tax/adjustment
            if ($item->item_type === self::ITEM_DISCOUNT) {
                // Ensure discounts are negative lines
                $amount = (float) ($item->quantity ?? 1) * (float) ($item->unit_price ?? 0);
                $item->line_total = -abs($amount);
            } else {
                $item->line_total = (float) ($item->quantity ?? 1) * (float) ($item->unit_price ?? 0);
            }
        });
    }
}
