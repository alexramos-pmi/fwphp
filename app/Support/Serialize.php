<?php

namespace App\Support;

class Serialize
{
    public static function Go(array $array)
    {
        return serialize($array);
    }

    public static function Back(string $string)
    {
        return unserialize($string, ['']);
    }
}
