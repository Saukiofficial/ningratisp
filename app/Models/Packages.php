<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Packages extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'is_purchasable' => 'boolean'
    ];

    public function customerPackages(): HasMany
    {
        return $this->hasMany(CustomerPackages::class);
    }

    public function pppProfile(): BelongsTo
    {
        return $this->belongsTo(PppProfile::class);
    }
}
