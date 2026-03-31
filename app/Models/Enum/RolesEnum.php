<?php

namespace App\Models\Enum;

enum RolesEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case TEKNISI = 'technician';
    case ADMIN_SUPPORT = 'support';

    public function label(): string
    {
        return match ($this) {
            static::SUPER_ADMIN => 'Super Admin',
            static::TEKNISI => 'Teknisi',
            static::ADMIN_SUPPORT => 'Admin Support'
        };
    }
}
