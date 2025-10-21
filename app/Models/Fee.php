<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    // unit
    const NOMINAL = 'n';
    const PERCENTAGE = 'p';

    public static function feeUnitLabel(): array
    {
        return [
            self::NOMINAL => 'Harga Tetap',
            self::PERCENTAGE => 'Persen'
        ];
    }
}
