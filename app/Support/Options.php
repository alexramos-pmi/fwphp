<?php

namespace App\Support;

class Options
{
    public static function Levels()
    {
        return [
            1 => 'Operador',
            2 => 'Admin'
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

    public static function eventoTipos()
    {
        return [
            1 => ['nome' => 'Letivo', 'cor' => '#87CEEB', 'prioridade' => 1, 'excluir_fim_de_semana' => 1],
            2 => ['nome' => 'Sábado Letivo', 'cor' => '#CD5C5C', 'prioridade' => 1, 'excluir_fim_de_semana' => 0],
            3 => ['nome' => 'Semana Pedagógica', 'cor' => '#32CD32', 'prioridade' => 2, 'excluir_fim_de_semana' => 0],
            4 => ['nome' => 'Férias', 'cor' => '#ffc966', 'prioridade' => 2, 'excluir_fim_de_semana' => 0],
            5 => ['nome' => 'Feriados', 'cor' => '#4B0082', 'prioridade' => 3, 'excluir_fim_de_semana' => 0],
            6 => ['nome' => 'Encontro Com Gestores Escolares', 'cor' => '#00FF7F', 'prioridade' => 2, 'excluir_fim_de_semana' => 1],
            7 => ['nome' => 'Planejamento nas Unidades Escolares', 'cor' => '#4169E1', 'prioridade' => 2, 'excluir_fim_de_semana' => 1],
            8 => ['nome' => 'Início e Término do Ano Letivo', 'cor' => '#228B22', 'prioridade' => 0, 'excluir_fim_de_semana' => 1],
            10 => ['nome' => 'Recesso de Carnaval', 'cor' => '#800080', 'prioridade' => 3, 'excluir_fim_de_semana' => 0],
            11 => ['nome' => 'Recesso de Semana Santa', 'cor' => '#FF8C00', 'prioridade' => 3, 'excluir_fim_de_semana' => 0],
            12 => ['nome' => 'Recesso Junino', 'cor' => '#708090', 'prioridade' => 3, 'excluir_fim_de_semana' => 0],
            13 => ['nome' => 'Recuperação', 'cor' => '#FF8C00', 'prioridade' => 2, 'excluir_fim_de_semana' => 1],
            14 => ['nome' => 'Conselho de Classe', 'cor' => '#800000', 'prioridade' => 2, 'excluir_fim_de_semana' => 1],
            15 => ['nome' => 'Publicação do Resultado Final', 'cor' => '#778899', 'prioridade' => 2, 'excluir_fim_de_semana' => 1],
            16 => ['nome' => 'Início das Unidades Letivas', 'cor' => '#999999', 'prioridade' => 0, 'excluir_fim_de_semana' => 1]
        ];
    }

    public static function situacao()
    {
        return [
            1 => 'Ativo(a)',
            2 => 'Inativo(a)'
        ];
    }

    public static function tipoDeAtendimento()
    {
        return [
            1 => 'Escolarização',
            2 => 'Atividade complementar',
            3=> 'Atendimento Educacional Especializado (AEE)'
        ];
    }

    public static function authAdmin()
    {
        return [2];
    }

    public static function authOperador()
    {
        return [1,2];
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
            1 => 'IN. NAT.',
            2 => 'COAG.',
            3 => 'DEC.',
            4 => 'FILT.',
            5 => 'CLOR.'
        ];
    }
}
