<?php

namespace App\Support;

class ConvertDate
{
    public static function toBRA(string $date): string
    {
        if(empty($date))
        {
            return '';
        }

        return date('d/m/Y', strtotime($date));
    }

    public static function toUSA(string $date): string
    {
        return self::calcToUsa($date);
    }

    public static function toBRAfull(string $dia, string $mes, string $ano): string
    {
        if(empty($dia) || empty($mes) || empty($ano))
        {
            return '';
        }

        $dia = $dia < 10 ? "0$dia" : $dia;
        $mes = $mes < 10 ? "0$mes" : $mes;

        return "$dia/$mes/$ano";
    }

    public static function toUSAfull(string $dia, string $mes, string $ano)
    {
        $date = "$dia/$mes/$ano";

        return self::calcToUsa($date);
    }

    //Privates

    private static function calcToUsa(string $date)
    {
        if(empty($date))
        {
            return '';
        }

        list($day, $month, $year) = explode('/', $date);

        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }
}
