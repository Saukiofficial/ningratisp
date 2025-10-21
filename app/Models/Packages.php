<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Packages extends BaseModel
{
    public function customerPackages(): HasMany
    {
        return $this->hasMany(CustomerPackages::class);
    }

    public function pppProfile(): BelongsTo
    {
        return $this->belongsTo(PppProfile::class);
    }
}
