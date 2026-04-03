<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerInstallationOrder extends Model
{
    const STATUS_PENDING = 'pending';

    const STATUS_ON_PROGRESS = 'onprogress';

    const STATUS_DONE = 'done';

    const STATUS_CANCELLED = 'cancelled';

    protected $guarded = ['id'];

    public static function getStatusLabel(): array
    {
        return [
            self::STATUS_PENDING => 'Pending (To-do)',
            self::STATUS_ON_PROGRESS => 'On Progress',
            self::STATUS_DONE => 'Done',
            self::STATUS_CANCELLED => 'Cancelled / Rescheduled',
        ];
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(PppArea::class, 'ppp_area_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Packages::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
