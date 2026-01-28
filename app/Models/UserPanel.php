<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserPanel extends Model
{
    const ROUTER = 'router';
    const ADMIN = 'admin';

    protected $guarded = ['id'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function panels(): array
    {
        return [
            self::ROUTER => 'Router',
            self::ADMIN => 'Admin'
        ];
    }
}
