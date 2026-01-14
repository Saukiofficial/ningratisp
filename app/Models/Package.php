<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'speed',
        'price',
        'description',
        'features',
        'status',
    ];

    // Otomatis convert JSON ke Array saat diakses
    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
    ];

    // Relasi: Satu paket bisa dimiliki banyak customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
