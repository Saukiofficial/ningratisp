<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerPackages extends BaseModel
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SUSPENDED = 'suspended';

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'auto_renew' => 'boolean',
    ];

    public static function getStatusLabel(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_SUSPENDED => 'Ditangguhkan'
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoices::class, 'customer_package_id');
    }
}
