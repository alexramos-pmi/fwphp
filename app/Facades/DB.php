<?php

namespace App\Facades;

use Illuminate\Database\Capsule\Manager as Capsule;

class DB
{
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([Capsule::class, $method], $args);
    }
}
