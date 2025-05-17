<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class ArrayUnique
{
    public static function Run(array $array)
    {
        $response = [];

        if(count($array) > 0)
        {
            foreach($array as $ar)
            {
                if(!in_array($ar, $response))
                {
                    $response[] = $ar;
                }
            }
        }

        return $response;
    }

    public static function RunModel($model, $column)
    {
        $arrayMounted = [];
        $response = [];

        if($model->count())
        {
            foreach($model as $m)
            {
                $arrayMounted[] = $m->$column;
            }

            foreach($arrayMounted as $am)
            {
                if(!in_array($am, $response))
                {
                    $response[] = $am;
                }
            }
        }

        return $response;
    }
}
