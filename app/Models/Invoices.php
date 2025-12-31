<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoices extends BaseModel
{
    use HasFactory;

    // const invoice status
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    // const invoice type
    const TYPE_MONTHLY = 'monthly';
    const TYPE_LOAN = 'loan_settlement';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_MANUAL = 'manual';

    // Const report type
    const REPORT_MONTHLY = 'monthly';
    const REPORT_ANNUALY = 'annualy';
    const REPORT_DATE_RANGE = 'date_range';

    public static function getStatusLabel(): array
    {
        return [
            self::STATUS_UNPAID => 'Belum bayar',
            self::STATUS_PAID => 'Sudah bayar',
            self::STATUS_OVERDUE => 'Mencapai batas bayar',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function getPaymentStatusLabel(): array
    {
        return [
            'unpaid' => 'Belum bayar',
            'partial' => 'Sebagian',
            'paid' => 'Lunas',
            'overpaid' => 'Kelebihan',
            'refunded' => 'Dikembalikan',
        ];
    }

    public static function getInvoiceTypeLabel(): array
    {
        return [
            self::TYPE_MONTHLY => 'Bulanan',
            self::TYPE_LOAN => 'Penyelesaian Pinjaman',
            self::TYPE_ADJUSTMENT => 'Penyesuaian',
            self::TYPE_MANUAL => 'Manual',
        ];
    }

    public static function getInvoiceReportLabel(): array
    {
        return [
            self::REPORT_MONTHLY => 'Laporan Bulanan',
            self::REPORT_ANNUALY => 'Laporan Tahunan',
            self::REPORT_DATE_RANGE => 'Laporan jarak waktu',
        ];
    }

    protected $guarded = ['id'];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    // Relationships
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class, 'invoice_id');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(
            Payment::class,
            PaymentAllocation::class,
            'invoice_id',
            'payment_id'
        )
            ->withPivot('amount', 'allocated_at');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function customerPackage(): BelongsTo
    {
        return $this->belongsTo(CustomerPackages::class, 'customer_package_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $invoice) {
            $invoice->recalculateTotals();
        });
    }

    public function recalculateTotals(): void
    {
        // When items exist, compute from items; otherwise keep existing amounts
        $hasItems = $this->items()->exists();

        if ($hasItems) {
            $charges = (float) $this->items()
                ->whereIn('item_type', ['charge', 'adjustment'])
                ->sum('line_total');

            // Discounts are stored as negative line_total; accumulate absolute value
            $discountLines = (float) $this->items()
                ->where('item_type', 'discount')
                ->sum('line_total');

            $taxLines = (float) $this->items()
                ->where('item_type', 'tax')
                ->sum('line_total');

            $this->subtotal = round($charges, 2);
            // $this->discount_amount = round(abs($discountLines), 2);
            $this->tax_amount = round($taxLines, 2);
            $this->total_amount = round($this->subtotal - $this->discount_amount + $this->tax_amount, 2);
        }

        // Payments: allocations + direct payments linked to invoice
        // $allocated = (float) $this->allocations()->sum('amount');
        // $directIncoming = (float) $this->payments()->where('is_cancel', false)->where('payment_type', 'incoming')->sum('total_amount');
        // $directRefunds = (float) $this->payments()->where('is_cancel', false)->where('payment_type', 'refund')->sum('total_amount');
        // $directPayments = $directIncoming - $directRefunds;
        // $this->paid_amount = round($allocated + $directPayments, 2);
        // dd($allocated, $this->paid_amount, $directIncoming, $directPayments);
        $this->paid_amount = round((float) $this->allocations()->sum('amount'), 2);

        $this->balance_due = max(round(($this->total_amount ?? 0) - $this->paid_amount, 2), 0.0);

        // Derive payment_status if column exists in schema
        if (isset($this->attributes['payment_status'])) {
            if ($this->paid_amount <= 0) {
                $this->payment_status = Payment::STATUS_UNPAID;
            } elseif ($this->paid_amount + 0.0001 < ($this->total_amount ?? 0)) {
                $this->payment_status = Payment::STATUS_PARTIAL;
            } elseif ($this->paid_amount - 0.0001 > ($this->total_amount ?? 0)) {
                $this->payment_status = Payment::STATUS_OVERPAID;
            } else {
                $this->payment_status = Payment::STATUS_PAID;
            }
        }

        $this->status = $this->paid_amount >= $this->total_amount ?
            self::STATUS_PAID : self::STATUS_UNPAID;
    }
}
