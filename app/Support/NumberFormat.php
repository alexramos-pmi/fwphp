<?php

namespace App\Support;

class NumberFormat
{
    public static function render($value, int $decimals)
    {
        return $value ? number_format($value, $decimals, ',', '.') : '';
    }
}