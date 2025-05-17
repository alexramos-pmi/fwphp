<?php

namespace App\Support;

class InstallmentValue
{
    public static function Run(float $valor, float $rate)
    {
        return ((100 * $valor) / (100 - $rate));
    }
}
