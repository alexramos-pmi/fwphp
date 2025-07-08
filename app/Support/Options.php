<?php

namespace App\Support;

class Options
{
    public static function Levels()
    {
        return [
            1 => 'Operador',
            2 => 'Local',
            3 => 'Regional',
            4 => 'Admin',
        ];
    }

    public static function YesNot()
    {
        return [
            1 => 'Sim',
            2 => 'Não',
        ];
    }

    public static function Measures()
    {
        return [
            1 => 'Linear',
            2 => 'M²',
            3 => 'M³',
            4 => 'Unitário'
        ];
    }

    public static function payments()
    {
        return [
            1 => 'À vista',
            2 => 'À prazo'
        ];
    }

    public static function person()
    {
        return [
            1 => 'Física',
            2 => 'Jurídica'
        ];
    }

    public static function situacao()
    {
        return [
            1 => 'Ativo(a)',
            2 => 'Inativo(a)'
        ];
    }

    public static function estadosCivis()
    {
        return [
            1 => ['id' => 1, 'nome' => 'Solteiro'],
            2 => ['id' => 2, 'nome' => 'Casado'],
            3 => ['id' => 3, 'nome' => 'Divorciado'],
            4 => ['id' => 4, 'nome' => 'Viúvo'],
            5 => ['id' => 5, 'nome' => 'Concubinato'],
            6 => ['id' => 6, 'nome' => 'União Estável']
        ];
    }

    public static function racas()
    {
        return [
            1 => ['id' => 1, 'nome' =>  'Amarela'] ,
            2 => ['id' => 2, 'nome' =>  'Branca'] ,
            3 => ['id' => 3, 'nome' =>  'Indígena'] ,
            4 => ['id' => 4, 'nome' =>  'Parda'] ,
            5 => ['id' => 5, 'nome' =>  'Preta']
        ];
    }

    public static function diasDaSemana()
    {
        return [
            0 => ['id' => 1, 'nome' => 'Domingo', 'abrev' =>'Dom'],
            1 => ['id' => 2, 'nome' => 'Segunda', 'abrev' =>'Seg'],
            2 => ['id' => 3, 'nome' => 'Terça', 'abrev' =>'Ter'],
            3 => ['id' => 4, 'nome' => 'Quarta', 'abrev' =>'Qua'],
            4 => ['id' => 5, 'nome' => 'Quinta', 'abrev' =>'Qui'],
            5 => ['id' => 6, 'nome' => 'Sexta', 'abrev' =>'Sex'],
            6 => ['id' => 7, 'nome' => 'Sábado', 'abrev' =>'Sáb']
        ];
    }

    public static function variacao()
    {
        return [
            1 => 'Aumento',
            2 => 'Redução',
            3 => 'Fechamento'
        ];
    }

    public static function bombas()
    {
        return [
            1 => '01',
            2 => '02',
            3 => '03',
            4 => '04',
        ];
    }

    public static function turnos()
    {
        return [
            1 => '00:00 - 07:00',
            2 => '07:00 - 19:00',
            3 => '19:00 - 00:00'
        ];
    }

    public static function caracts()
    {
        return [
            1 => 'PH',
            2 => 'ALC.',
            3 => 'TURBIDEZ',
            4 => 'COR',
            5 => 'AL ou Fe-res.',
            6 => 'CL. R.',
            7 => 'FLÚOR'
        ];
    }

    public static function amostras()
    {
        return [
            1 => 'ETA',
            2 => 'IN. NAT.',
            3 => 'COAG.',
            4 => 'DEC.',
            5 => 'FILT.',
            6 => 'CLOR.'
        ];
    }

    public static function meses()
    {
        return [
            0 => ['id' => '01','abrev' => 'Jan', 'nome' => 'Janeiro'],
            1 => ['id' => '02','abrev' => 'Fev', 'nome' => 'Fevereiro'],
            2 => ['id' => '03','abrev' => 'Mar', 'nome' => 'Março'],
            3 => ['id' => '04','abrev' => 'Abr', 'nome' => 'Abril'],
            4 => ['id' => '05','abrev' => 'Mai', 'nome' => 'Maio'],
            5 => ['id' => '06','abrev' => 'Jun', 'nome' => 'Junho'],
            6 => ['id' => '07','abrev' => 'Jul', 'nome' => 'Julho'],
            7 => ['id' => '08','abrev' => 'Ago', 'nome' => 'Agosto'],
            8 => ['id' => '09','abrev' => 'Set', 'nome' => 'Setembro'],
            9 => ['id' => '10','abrev' => 'Out', 'nome' => 'Outubro'],
            10 => ['id' => '11','abrev' => 'Nov', 'nome' => 'Novembro'],
            11 => ['id' => '12','abrev' => 'Dez', 'nome' => 'Dezembro']
        ];
    }
}
