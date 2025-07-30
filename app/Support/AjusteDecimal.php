<?php 

namespace App\Support;

class AjusteDecimal
{
    public static function render($value)
    {
        return $value ? str_replace(',', '.', str_replace('.', '', $value)) : '';
    }

    public static function usa($value)
    {
        return $value ? str_replace(',', '.', str_replace('.', '', $value)) : null;
    }
}