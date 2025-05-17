<?php

namespace App\Support;

class ColorHexadecimal
{
    public static function Run()
    {
        return sprintf('#%06x', random_int(0, 0xFFFFFF));
    }
}
