<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'title',
        'message',
        'status',
    ];

    // Relasi: Complaint milik satu Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
