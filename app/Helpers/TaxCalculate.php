<?php

namespace App\Helpers;

class TaxCalculate
{
    // unit
    const NOMINAL = 'n';
    const PERCENTAGE = 'p';

    public static function calculate($price, $value, $unit = self::NOMINAL)
    {
        if ($unit == self::PERCENTAGE) {
            $total = $price + (($price * $value) / 100);
        } else {
            $total = intval($price +  $value);
        }

        return $total;
    }

    /**
     * 
     */
    public static function getLabelTax(int|float $value, $unit = self::NOMINAL, $prefix = 'Rp ')
    {
        $total = $unit == self::NOMINAL ?
            $prefix . number_format($value, 0, ',', '.') : (empty($value) ? '-' : $value . '%');

        return $total;
    }

    public static function setTax() {}
}
