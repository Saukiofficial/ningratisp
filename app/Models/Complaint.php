<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subject', 'description', 'status'];

    /**
     * Relasi: Komplain dimiliki oleh satu user.
     * Fungsi inilah yang dicari oleh controller saat memanggil with('user')
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
