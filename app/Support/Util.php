<?php

namespace App\Support;

use DateTime;

class Util
{
    public static function shortName($fullName)
    {
        $explodeFullName = explode(' ', $fullName);

        if($explodeFullName)
        {
            return reset($explodeFullName) . ' ' . end($explodeFullName);
        }

        return '';
    }

    public static function cpfToNumber($cpf)
    {
        return preg_replace('/\D/', '', $cpf);
    }

    public static function cpfToString($cpf)
    {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    /**
     * $start2 a $end2 deverá está contido em $start1 a $end1
     */
    public static function checaDataEmPeriodo($start1, $end1 , $start2, $end2)
    {
        $startPeriod1 = new DateTime($start1);
        $endPeriod1 = new DateTime($end1);

        $startPeriod2 = new DateTime($start2);
        $endPeriod2 = new DateTime($end2);

        if($startPeriod2 >= $startPeriod1 && $endPeriod2 <= $endPeriod1)
        {
            return true;
        }

        return false;
    }

    public static function valMaxDesempenho()
    {
        return [
            'instrumento' => 10,
            'periodo' => 5,
            'ano' => 15
        ];
    }
}
