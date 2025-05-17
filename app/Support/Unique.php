<?php

namespace App\Support;

class Unique
{
    public static function Run($model, array $columns, array $values, $id = false)
    {
        $count = count($columns);

        for($i = 0; $i < $count; $i++)
        {
            $query[] = [$columns[$i], '=', $values[$i]];
        }

        $response = $model->where($query)->first();

        if($response)
        {
            if($id || $id > 0)
            {
                return ($id != $response->id ? $response : false);
            }
            else
            {
                return $response;
            }
        }

        return false;
    }
}
