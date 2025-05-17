<?php

namespace App\Support;

class CheckDate
{
    public static function Run($date)
    {
        $exDate = explode('/', $date);

        if(count($exDate) == 3)
        {
            if(strlen($date) == 10)
            {
                return checkdate($exDate[1], $exDate[0], $exDate[2]);
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}
