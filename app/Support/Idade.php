<?php

namespace App\Support;

class Idade
{
    public static function show($date, $us = false, $year = false)
    {
        if($date):

            if(!$us):

                $AnoIdade = explode('/', $date);

                if(count($AnoIdade) != 3)
                {
                    return null;
                }

                $dia = $AnoIdade[0];
                $mes = $AnoIdade[1];
                $ano = $AnoIdade[2];

            else:

                $AnoIdade = explode('-', $date);

                if(count($AnoIdade) != 3)
                {
                    return null;
                }

                $dia = $AnoIdade[2];
                $mes = $AnoIdade[1];
                $ano = $AnoIdade[0];

            endif;

            if($year && $year != date('Y'))
            {
                $m = 12;
                $y = $year;

                $dataAtual = '31/12/' . $year;
                $atual = explode('/', $dataAtual);
            }
            else
            {
                $m = date('m');
                $y = date('Y');

                $atual = explode('/', date('d/m/Y'));
            }

            if(($atual[2] - $ano) == 0):

                if(($atual[1] - $mes) == 0):

                    return ($atual[0] - $dia) . ' dias';

                elseif(($atual[1] - $mes) == 1):

                    if($dia <= $atual[0]):

                        return '1 mês';

                    elseif($dia > $atual[0]):

                        return ((self::diasMeses($mes, $ano) - $dia) + $atual[0]) . ' dias';

                    endif;

                elseif(($atual[1] - $mes) > 1):

                    return ($atual[1] - $mes) . ' meses';

                endif;

            elseif(($atual[2] - $ano) == 1):

                if(((12 - $mes) + $atual[1]) >= 12):

                    return ($atual[2] - $ano) . ' ano';

                elseif(((12 - $mes) + $atual[1]) > 1 && ((12 - $mes) + $atual[1]) < 12):

                    return ((12 - $mes) + $atual[1]) . ' meses';

                elseif(((12 - $mes) + $atual[1]) == 1):

                    if($dia <= $atual[0]):

                        return '1 mês';

                    elseif($dia > $atual[0]):

                        return (31 - $dia) + $atual[0] . ' dias';

                    endif;

                endif;

            elseif(($atual[2] - $ano) > 1):

                // CASO O ANO SEJA MAIOR QUE 1, CONFIGURA DE ACORDO COM OS MESES E OS DIAS
                if($atual[0] >= $dia):

                    if($atual[1] >= $mes):

                        if(($y - $ano) > 0):

                            return ($y - $ano) . ' anos';

                        elseif(($m - $mes) > 0):

                            return ($m - $mes) . ' meses';

                        else:

                            return ($m - $mes) . ' meses';

                        endif;

                    else:

                        return (($y - $ano) - 1) . ' anos';

                    endif;

                else:

                    if($atual[1] > $mes):

                        return ($y - $ano) . ' anos';

                    else:

                        return (($y - $ano) - 1) . ' anos';

                    endif;

                endif;

            endif;

        else:

            return NULL;

        endif;
    }

    private static function diasMeses($mes, $ano)
    {
        $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));

        if($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12):

            return 31;

        elseif($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11):

            return 30;

        elseif($mes == 2 && $bissexto):

            return 29;

        else:

            return 28;

        endif;
    }
}
