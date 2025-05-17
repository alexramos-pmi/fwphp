<?php

namespace App\Support;

class ProporcaoImagem
{
    public static function Run($prop, $altura)
    {
        if($prop == 2)
        {
            $largura = $altura * 1.7;

            return ['L' => $largura, 'A' => $altura];
        }

        return ['L' => $altura, 'A' => $altura];
    }
}
