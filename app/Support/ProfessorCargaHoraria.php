<?php

namespace App\Support;

use App\Models\TurmaModel;
use App\Models\HoraAulaPeriodoModel;
use App\Models\FuncionarioMatriculaModel;
use App\Models\TurmaHorarioModel;
use DateTime;

class ProfessorCargaHoraria
{
    private $turma;
    private $matricula;
    private $aula;

    public function __construct($turma, $matricula, $aula)
    {
        $this->turma = TurmaModel::find($turma);
        $this->matricula = FuncionarioMatriculaModel::find($matricula);
        $this->aula = $aula;
    }

    public function verificaCargaHoraria()
    {
        $ch = $this->cargaHorariaReal() * 60;
        $status = $this->calcChParcial() + $this->calcChParaGravar();

        return $status >= $ch ? true : false;
    }

    public function chParcial()
    {
        return $this->calcChParcial();
    }

    public function chParaGravar()
    {
        return $this->calcChParaGravar();
    }

    //Getters

    public function exibeStatus()
    {
        return $this->converteMinutosParaHoras();
    }

    //Privates

    private function converteMinutosParaHoras()
    {
        $ch = $this->cargaHorariaReal();

        $chParcial = $this->calcChParcial();

        $horaCH = (int) ($chParcial / 60);
        $minutoCH = $chParcial % 60;

        $horaCH = $horaCH < 10 ? "0{$horaCH}" : $horaCH;
        $minutoCH = $minutoCH < 10 ? "0{$minutoCH}" : $minutoCH;

        return "{$horaCH}:{$minutoCH} de {$ch}:00";
    }

    private function cargaHorariaReal()
    {
        return $this->matricula->ch == 20 ? 13 : 26;
    }

    private function calculaTempoHoraAula($inicio, $fim)
    {
        //Define os horários
        $horaInicial = new DateTime($inicio);
        $horaFinal = new DateTime($fim);

        //Calcula a diferença entre os horários
        $diferenca = $horaInicial->diff($horaFinal);

        //Converte a diferença para minutos
        return ($diferenca->h * 60) + $diferenca->i;
    }

    private function calcChParcial()
    {
        $horarios = TurmaHorarioModel::where('matricula', $this->matricula->id)->where('acao', 1)->get();

        $response = 0;

        if($horarios->count())
        {
            foreach($horarios as $horario)
            {
                $horaAula = HoraAulaPeriodoModel::where('hora_aula', $horario->turma()->hora_aula)
                ->where('aula', $horario->aula)
                ->first();

                $response += $this->calculaTempoHoraAula($horaAula->inicio, $horaAula->fim);
            }
        }

        return $response;
    }

    private function calcChParaGravar()
    {
        $horario = HoraAulaPeriodoModel::where('hora_aula', $this->turma->hora_aula)
        ->where('aula', $this->aula)
        ->first();

        return $this->calculaTempoHoraAula($horario->inicio, $horario->fim);
    }
}
