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
        'price' => 'integer', // Ubah dari decimal:2 ke integer untuk hilangkan .00
    ];

    // Accessor untuk format harga dengan pemisah ribuan
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.');
    }

    // Relasi: Satu paket bisa dimiliki banyak customer
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
