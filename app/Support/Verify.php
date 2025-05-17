<?php

namespace App\Support;

class Verify
{
    public static function Empty($value)
    {
        return empty($value) || $value === 'null' || $value === 'undefined' || is_null($value);
    }
}
