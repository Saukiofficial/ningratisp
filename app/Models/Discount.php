<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{

    use HasFactory;

    // applicable on
    const FOR_INVOICE = 'invoice';
    const FOR_PACKAGE = 'package';
    const FOR_CUSTOMER = 'customer';

    // category for user
    const TYPE_FREE_FOREVER = 'free_forever';
    const TYPE_LOAN = 'loan';
    const TYPE_NORMAL = 'normal';

    // type
    const PERCENTAGE = 'percentage';
    const FIXED_AMOUNT = 'fixed_amount';

    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'auto_apply' => 'boolean',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Packages::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoices::class);
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, CustomerDiscount::class)
            ->withTimestamps();
    }

    public function customerDiscounts(): HasMany
    {
        return $this->hasMany(CustomerDiscount::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAutoApplicable($query)
    {
        $today = now()->toDateString();
        return $query->active()
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            });
    }

    public function isWithinDateRange(): bool
    {
        $today = now()->toDateString();
        if ($this->start_date && $this->start_date->toDateString() > $today) return false;
        if ($this->end_date && $this->end_date->toDateString() < $today) return false;
        return true;
    }

    public static function getApplicableStatus(): array
    {
        return [
            self::FOR_CUSTOMER => 'Customer',
            self::FOR_INVOICE => 'Invoice',
            self::FOR_PACKAGE => 'Package'
        ];
    }

    public static function getCategoryStatus(): array
    {
        return [
            self::TYPE_FREE_FOREVER => 'Free Forever',
            self::TYPE_LOAN => 'Loan / Adjustment',
            self::TYPE_NORMAL => 'Normal (Default)'
        ];
    }

    public static function getAmountType(): array
    {
        return [
            self::PERCENTAGE => 'Percentage (%)',
            self::FIXED_AMOUNT => 'Fixed Price (Rp)'
        ];
    }

    public static function generateRandomCode($length = 10): string
    {
        return strtoupper(fake()->bothify(
            collect(
                str_split(str_repeat('#?', $length))
            )->random($length)->join('')
        ));
    }
}
