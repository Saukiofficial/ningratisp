<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerConnection extends BaseModel
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
