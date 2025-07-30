<?php

namespace App\Support;

class Substr
{
    public static function index(string $value, int $offset, $length = null)
    {
        return $value ? substr($value, $offset, $length) : '&nbsp;';
    }
}