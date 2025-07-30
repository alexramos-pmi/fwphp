<?php

namespace App\Support;

class ValidaRetorno
{
    public static function index($valor, $retorno)
    {
        return $valor ? $valor : $retorno;
    }
}