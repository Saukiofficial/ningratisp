<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'package_id',
        'status',
    ];

    // Relasi: Customer adalah milik satu User (Akun Login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Customer berlangganan satu Package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relasi: Customer bisa punya banyak Complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
