<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Packages extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'is_purchasable' => 'boolean',
    ];

    public function customerPackages(): HasMany
    {
        return $this->hasMany(CustomerPackages::class);
    }

    public function pppProfile(): BelongsTo
    {
        return $this->belongsTo(PppProfile::class);
    }

    public function packageLabel(): Attribute
    {
        return new Attribute(
            get: fn () => $this->pppProfile->profile_name." ($this->name)"
        );
    }

    public static function getDropDownWithPpp(): array
    {
        return self::query()
            ->with('pppProfile')
            ->where('is_purchasable', true)
            ->whereHas('pppProfile')
            ->get()
            ->mapWithKeys(function ($package) {
                $profileName = $package->pppProfile?->profile_name ?? '-';

                return [
                    $package->id => "{$profileName} ({$package->name})",
                ];
            })
            ->toArray();
    }
}
