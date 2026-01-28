<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const ONPROGRESS = 'onprogress';
    const CANCELLED = 'cancelled';
    const DONE = 'done';

    protected $guarded = ['id'];

    /**
     * Relasi: Komplain dimiliki oleh satu user.
     * Fungsi inilah yang dicari oleh controller saat memanggil with('user')
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public static function getStatusLabel(): array
    {
        return [
            self::PENDING => 'Pending (Menunggu)',
            self::ONPROGRESS => 'On-Progress (Sedang dikerjakan)',
            self::CANCELLED => 'Cancelled (Dibatalkan)',
            self::DONE => 'Done (Selesai)',
        ];
    }
}
