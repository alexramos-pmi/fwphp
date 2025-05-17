<?php

namespace App\Support;

use App\Models\TurmaModel;
use Illuminate\Support\Facades\DB;

class ComponenteAulaMax
{
    private $turma;
    private $componente;

    public function __construct($turma, $componente)
    {
        $this->turma = TurmaModel::find($turma);
        $this->componente = DB::table('componente_curricular_models')->find($componente);
    }

    public function maxAulas()
    {
        $matrizSerieComponentes = $this->turma->matriz()->serie()->componentes();

        $campoDesejado = 'componente_curricular';
        $valorProcurado = $this->componente->id;
        $qtdAulaSemanal = 0;

        if($matrizSerieComponentes->count())
        {
            foreach($matrizSerieComponentes as $objeto)
            {
                if (isset($objeto->$campoDesejado) && $objeto->$campoDesejado === $valorProcurado)
                {
                    $qtdAulaSemanal = $objeto->qtd_aula_semanal;
                    break;
                }
            }
        }

        return $qtdAulaSemanal;
    }

    public function parcialAulas()
    {
        $turmaHorarios = $this->turma->horario($this->componente->id);

        return $turmaHorarios->count();
    }

    public function checkQtdAulas()
    {
        if(($this->parcialAulas() + 1) > $this->maxAulas())
        {
            return true;
        }

        return false;
    }
}
