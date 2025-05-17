<?php

namespace App\Support;

class Date
{
    public static function Complete($dia, $mes, $ano)
    {
        if(!empty($dia) && !empty($mes) && !empty($ano))
        {
            if($dia < 1 || $dia > 31)
            {
                return '';
            }

            if($mes < 1 || $mes > 12)
            {
                return '';
            }

            if(strlen($ano) != 4)
            {
                return '';
            }

            $dia = ($dia < 10 ? '0' . $dia : $dia);
            $mes = ($mes < 10 ? '0' . $mes : $mes);

            return $dia . '/' . $mes . '/' . $ano;
        }

        return '';
    }

    public static function Dia($date)
    {
        if(!empty($date))
        {
            $dateEx = explode('/', $date);

            if(count($dateEx) > 0 && $dateEx[0] >= 1 && $dateEx[0] <= 31)
            {
                return $dateEx[0];
            }

            return '';
        }

        return '';
    }

    public static function Mes($date)
    {
        if(!empty($date))
        {
            $dateEx = explode('/', $date);

            if(count($dateEx) > 0 && $dateEx[1] >= 1 && $dateEx[1] <= 12)
            {
                return $dateEx[1];
            }

            return '';
        }

        return '';
    }

    public static function Ano($date)
    {
        if(!empty($date))
        {
            $dateEx = explode('/', $date);

            if(count($dateEx) > 0 && strlen($dateEx[2]) == 4)
            {
                return $dateEx[2];
            }

            return '';
        }

        return '';
    }
}
