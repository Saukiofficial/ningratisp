<?php

namespace App\Helpers;

class NumberFormatter
{
    public static function humanReadable(float|int $number, string $prefix = ''): string
    {
        if ($number >= 1000000) {
            $value = round($number / 1000000, 1);
            $formattedValue = str_replace('.0', '', (string)$value);
            return $prefix . $formattedValue . 'Jt';
        }

        if ($number >= 1000) {
            $value = round($number / 1000, 1);
            $formattedValue = str_replace('.0', '', (string)$value);
            return $prefix . $formattedValue . 'Rb';
        }

        return $prefix . number_format($number, 0, '.', ',');
    }
}
